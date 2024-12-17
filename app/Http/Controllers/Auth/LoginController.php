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
use Illuminate\Support\Facades\Log;

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
        // Log the initial request for debugging
        Log::info('ndoptor_sso_callback called with data: ' . $request->data);

        // Decode and validate request data
        $data = json_decode(base64_decode($request->data), true);
        if (!isset($data['token'])) {
            Log::warning('Token not found in the callback data');
            return redirect()->route('doptor.login');
        }

        $token = $data['token'];
        session(['bearerToken' => $token]);

        // Fetch user information from API
        $response = $this->fetchUserInfoFromDoptor($token);
        if (!$response || $response->status !== 'success') {
            Log::warning('Invalid response from the API: ' . json_encode($response));
            return redirect()->route('doptor.login')->with('error', 'Failed to retrieve user information.');
        }

        $employeData = $response->data->user->employee_record_id;
        $doptoEmployeeUserImage = $this->doptorUserImage($employeData);

        $data['doptoEmployeeUserImage'] = json_decode($doptoEmployeeUserImage);

        $organogramInfo = $response->data->organogram_info ?? null;
        if (!$organogramInfo) {
            return redirect()->route('sso.logout')->with('message', 'Information not found.');
        }

        $organogramIds = collect($organogramInfo)->pluck('id')->toArray();

        if (!empty($organogramIds)) {
            // Query to get the data using the extracted IDs
            $organoGramUserInfo = DB::table('doptor_user_managements')
                ->select('id', 'organogram_id', 'user_role')
                ->whereIn('doptor_user_managements.organogram_id', $organogramIds)
                ->first();
        }

        // Assuming $response->data->office_info is an array of objects
        $officeInfo = null;
        if (is_array($response->data->office_info)) {
            foreach ($response->data->office_info as $info) {
                if ($info->office_unit_organogram_id == $organoGramUserInfo->organogram_id) {
                    // Found the matching office info
                    $officeInfo = $info;
                    break;
                }
            }
        }

        // Now process the matched $officeInfo
        if ($officeInfo) {
            $officeMinistryId = $officeInfo->office_ministry_id;
            $officeId = $officeInfo->office_id;
            $unitNameBn = $officeInfo->unit_name_bn;
            $designation = $officeInfo->designation;
        } else {
            return redirect()->route('sso.logout')->with('message', 'No matching office info found.');
        }

        $userData = [
            'name' => $response->data->employee_info->name_bng,
            'username' => $response->data->user->user_alias,
            'mobile_no' => $response->data->employee_info->personal_mobile,
            'email' => $response->data->employee_info->personal_email,
            'ministry' => $officeMinistryId,
            'signature' => null,
            'profile_image' => $data['doptoEmployeeUserImage']->data[0]->image ?? null,
            'role_id' => $organoGramUserInfo->user_role ?? 43,
            'office_id' => $officeId,
            'is_gov' => 1,
            'password' => Hash::make('!(MHL@9865@MMR#SCMS@)'),
            'unit_name_bn' => $unitNameBn,
            'designation' => $designation,
            'organogram_id' => $organoGramUserInfo->organogram_id ?? null,
            'employee_record_id' => $response->data->user->employee_record_id ?? null,
        ];

        User::updateOrInsert(['doptor_user_id' => $response->data->user->id], $userData);

        $user = User::where('doptor_user_id', $response->data->user->id)->first();
        if ($organoGramUserInfo && $organoGramUserInfo->user_role) {
            $user->syncRoles([]);
            $role = Role::find($organoGramUserInfo->user_role);
            $user->assignRole($role);
        }

        Auth::loginUsingId($user->id);
        return redirect()->route('dashboard');
    }

    private function fetchUserInfoFromDoptor($token)
    {
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
        if (curl_errno($curl)) {
            Log::error('cURL error: ' . curl_error($curl));
            return null;
        }

        curl_close($curl);
        return json_decode($response);
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

            // CURLOPT_URL => 'https://n-doptor-api.nothi.gov.bd/api/user/images',
            // CURLOPT_URL => 'https://apigw-stage.doptor.gov.bd/api/user/images',
            CURLOPT_URL => DOPTOR_ENDPOINT() . '/api/user/images',
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
                'apikey: YED1EN',
                'Authorization: Bearer ' . $token,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;

    }

}
