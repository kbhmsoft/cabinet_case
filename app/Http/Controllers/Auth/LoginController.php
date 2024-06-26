<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::DASHBOARD;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // public function doptorLogin(Request $request)
    // {

    //     $userEmail = $request->email;
    //     $password = $request->password;
    //     if (Auth::attempt(['email' => $userEmail, 'password' => $password])) {
    //         $user = Auth::user();
    //         $success['user_id'] = $user->id;
    //         return redirect()->route('dashboard');
    //     } else {
    //         return redirect()->back()->with('error', '!! User Credential Not Matched !!');
    //     }
    // }

    public function doptorLogin(Request $request)
    {
        // Validate the form data
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Retrieve the input data
        $login = $request->input('login');
        $password = $request->input('password');

        // Determine if the login is an email or phone number
        $loginType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile_no';

        // Check if the user exists
        $userExists = \App\Models\User::where($loginType, $login)->exists();

        if (!$userExists) {
            // If the user doesn't exist, return a specific error message
            $error = ['login' => 'আপনি ভুল ইমেইল/ফোন নম্বর দিয়েছেন'];
            return redirect()->back()->withErrors($error)->withInput($request->only('login', 'password'));
        }

        // Attempt to log the user in
        if (Auth::attempt([$loginType => $login, 'password' => $password])) {
            return redirect()->route('dashboard');
        } else {
            // If login fails due to incorrect password
            $error = ['password' => 'আপনি ভুল পাসওয়ার্ড দিয়েছেন'];
            return redirect()->back()->withErrors($error)->withInput($request->only('login', 'password'));
        }
    }

    public function initiateSSOLogin(Request $request)
    {
        $callbackurl = url('/') . '/nothi/callback';
        // $zoom_join_url = 'https://api-training.doptor.gov.bd' . '/v2/login?referer=' . base64_encode($callbackurl);
        $zoom_join_url = DOPTOR_ENDPOINT() . '/v2/login?referer=' . base64_encode($callbackurl);
        return redirect()->away($zoom_join_url);
    }

    public function ndoptor_sso_callback(Request $request)
    {
        $data_get_method = $request->data;
        $data = json_decode(base64_decode($request->data), true);
        $token = '';
        if (!isset($data['token'])) {
            return redirect()->route('doptor.login');
        } else {
            $token = $data['token'];
        }

        session(['bearerToken' => $token]);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => DOPTOR_ENDPOINT() . '/api/user/me',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Accept: application/json', 'api-version: 1', 'Authorization: Bearer ' . $token],
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);

        $employeData = $response->data->user->employee_record_id;

        $doptoEmployeeUserImage = $this->doptorUserImage($employeData);
        $data['doptoEmployeeUserImage'] = json_decode($doptoEmployeeUserImage);

        if ($response->status == 'success') {
            if (end($response->data->organogram_info)) {
                $id = end($response->data->organogram_info)->id;
            } else {
                return redirect()->route('sso.logout')->with('message', 'Information not found.');
            }

            $userInformationa = $response->data;
            $organogramId = key($userInformationa->organogram_info);

            $organoGramUserInfo = DB::table('doptor_user_managements')
                ->select('id', 'organogram_id', 'user_role')
                ->where('doptor_user_managements.organogram_id', $id)
                ->first();

            if ($id && $organoGramUserInfo && $organoGramUserInfo->user_role && $organoGramUserInfo->user_role != 42) {
                $userInfo = $response->data->user;
                $userEmployeeData = $response->data->employee_info;
                $userOfficeInfo = end($response->data->office_info);

                $userData = [
                    'name' => $userEmployeeData->name_bng,
                    'username' => $userInfo->user_alias,
                    'mobile_no' => $userEmployeeData->personal_mobile,
                    'email' => $userEmployeeData->personal_email,
                    'ministry' => $userOfficeInfo->office_ministry_id,
                    'signature' => null,
                    'profile_image' => $data['doptoEmployeeUserImage']->data[0]->image ?? null,
                    'role_id' => $organoGramUserInfo->user_role,
                    'office_id' => $userOfficeInfo->office_id,
                    'is_gov' => 1,
                    'password' => Hash::make('!(MHL@9865@MMR#CSMS@)'),
                    'unit_name_bn' => $userOfficeInfo->unit_name_bn,
                    'designation' => $userOfficeInfo->designation,
                    'organogram_id' => $organogramId ?? null,
                    'employee_record_id' => $userInfo->employee_record_id ?? null,
                ];

                User::updateOrInsert(
                    ['doptor_user_id' => $userInfo->id],
                    $userData
                );

                $user = User::where('doptor_user_id', $userInfo->id)->first();
                if ($organoGramUserInfo->user_role) {
                    $user->syncRoles([]);
                    $role = Role::find($organoGramUserInfo->user_role);
                    $user->assignRole($role);
                }

                Auth::loginUsingId($user->id);
                return redirect()->route('dashboard');

            } else if ($id && (!$organoGramUserInfo || !$organoGramUserInfo->user_role)) {

                $userInfo = $response->data->user;
                $userInformationa = $response->data;
                $userEmployeeData = $response->data->employee_info;
                $userOfficeInfo = end($response->data->office_info);
                $organogramId = key($userInformationa->organogram_info);

                $userData = [
                    'name' => $userEmployeeData->name_bng,
                    'username' => $userInfo->user_alias,
                    'mobile_no' => $userEmployeeData->personal_mobile,
                    'email' => $userEmployeeData->personal_email,
                    'ministry' => $userOfficeInfo->office_ministry_id,
                    'signature' => null,
                    'profile_image' => $data['doptoEmployeeUserImage']->data[0]->image ?? null,
                    'role_id' => 43,
                    'office_id' => $userOfficeInfo->office_id,
                    'is_gov' => 1,
                    'password' => Hash::make('!(MHL@9865@MMR#CSMS@)'),
                    'unit_name_bn' => $userOfficeInfo->unit_name_bn,
                    'designation' => $userOfficeInfo->designation,
                    'organogram_id' => $organogramId ?? null,
                    'employee_record_id' => $userInfo->employee_record_id ?? null,
                ];

                User::updateOrInsert(
                    ['doptor_user_id' => $userInfo->id],
                    $userData
                );

                // Retrieve the user after update/insert
                $user = User::where('doptor_user_id', $userInfo->id)->first();
                if ($organoGramUserInfo && $organoGramUserInfo->user_role) {
                    $role = Role::find($organoGramUserInfo->user_role);
                    $user->assignRole($role);
                }
                Auth::loginUsingId($user->id);

                return redirect()->route('dashboard');
            }
        }
    }

    public static function logout_doptor()
    {
        $callbackurl = url('/');
        $zoom_join_url = DOPTOR_ENDPOINT() . '/v2/logout?' . 'referer=' . base64_encode($callbackurl);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $zoom_join_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        curl_exec($curl);
        curl_close($curl);

        return;
    }

    public function doptorUserImage($employeeRecordId)
    {
        $curl = curl_init();
        $token = session('bearerToken');
        $employee_record_ids = $employeeRecordId;
        curl_setopt_array($curl, array(

            CURLOPT_URL => 'https://n-doptor-api.nothi.gov.bd/api/user/images',

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array('employee_record_ids' => $employee_record_ids)), // Encode array to JSON
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'api-version: 1',
                'apikey: 8XI1PI',
                'Authorization: Bearer ' . $token,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;

    }

}
