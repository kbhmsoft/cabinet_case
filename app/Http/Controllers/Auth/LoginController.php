<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
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

    /*public function index()
    {
    $user = Auth::user();
    dd($user);

    dd(Auth::user()->role_id);
    if(Auth::user()->role_id == 1){
    // Superadmi dashboard
    return view('dashboard.superadmin');

    }elseif(Auth::user()->role_id == 5){
    // DC office assistant dashboard
    return view('dashboard.do_asst');
    }
    }
     */
    //  public function showLoginForm(){
    //     return 'aaa';
    //  }

    public function doptorLogin(Request $request)
    {
        $userEmail = $request->email;
        $password = $request->password;

        if (Auth::attempt(['email' => $userEmail, 'password' => $password])) {
            $user = Auth::user();
            $success['user_id'] = $user->id;
            return redirect()->route('dashboard');
        } else {
            return redirect()->back()->with('error', '!! User Credential Not Matched !!');
        }
    }

    public function initiateSSOLogin(Request $request)
    {
        $callbackurl = url('/') . '/nothi/callback';
        $zoom_join_url = 'https://api-training.doptor.gov.bd' . '/v2/login?referer=' . base64_encode($callbackurl);
        return redirect()->away($zoom_join_url);
    }

    public function ndoptor_sso_callback(Request $request)
    {
        $data_get_method = $request->data;
        $data = json_decode(base64_decode($request->data), true);
        if (!isset($data['token'])) {
            return redirect()->route('nothi.v2.login');
        } else {
            $token = $data['token'];
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('LOGIN_API2') . '/api/user/me',
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

        if ($response->status == 'success') {

            $id = current($response->data->organogram_info)->id;
            $userData = $response->data;
            $organoGramUserInfo = DB::table('doptor_user_managements')
                ->select('id', 'organogram_id', 'user_role')
                ->where('doptor_user_managements.organogram_id', $id)
                ->first();

            // if ($id && $organoGramUserInfo && $organoGramUserInfo->user_role && $organoGramUserInfo->user_role != 42) {
            //     $userInfo = $response->data->user;

            //     $userEmployeeData = $response->data->employee_info;

            //     $userOfficeInfo = $response->data->office_info;

            //     $userOfficeInfo = $response->data->office_info;
            //     $userDataSave = '';

            //     if (!User::find($userInfo->id)) {
            //         $userDataSave = DB::table('users')->insert([
            //             'doptor_user_id' => $userInfo->id,
            //             'name' => $userEmployeeData->name_bng,
            //             'username' => $userInfo->user_alias,
            //             'mobile_no' => $userEmployeeData->personal_mobile,
            //             // 'designation_name' =>$user->designation_name->name_bn,
            //             'email' => $userEmployeeData->personal_email,
            //             'ministry' => $userOfficeInfo[0]->office_ministry_id,
            //             'signature' => null,
            //             'profile_image' => null,
            //             'role_id' => $organoGramUserInfo->user_role,
            //             'office_id' => $userOfficeInfo[0]->office_id,
            //             'is_gov' => 1,
            //             'password' => Hash::make('!(MHL@9865@MMR#CSMS@)'),
            //         ]);
            //     } else {
            //         $userDataSave = DB::table('users')
            //             ->where('doptor_user_id', $userInfo->id)
            //             ->update([
            //                 'name' => $userEmployeeData->name_bng,
            //                 'username' => $userInfo->user_alias,
            //                 'mobile_no' => $userEmployeeData->personal_mobile,
            //                 // 'designation_name' =>$user->designation_name->name_bn,
            //                 'email' => $userEmployeeData->personal_email,
            //                 'ministry' => $userOfficeInfo[0]->office_ministry_id,
            //                 'signature' => null,
            //                 'profile_image' => null,
            //                 'role_id' => $organoGramUserInfo->user_role,
            //                 'office_id' => $userOfficeInfo[0]->office_id,
            //                 'is_gov' => 1,
            //                 'password' => Hash::make('!(MHL@9865@MMR#CSMS@)'),
            //             ]);
            //     }

            //     $user = User::where('doptor_user_id', $userInfo->id)->first();
            //     Auth::loginUsingId($user->id);
            //     return redirect()->route('dashboard');
            // }
            if ($id && $organoGramUserInfo && $organoGramUserInfo->user_role && $organoGramUserInfo->user_role != 42) {
                $userInfo = $response->data->user;
                $userEmployeeData = $response->data->employee_info;
                $userOfficeInfo = $response->data->office_info;

                $userData = [
                    'name' => $userEmployeeData->name_bng,
                    'username' => $userInfo->user_alias,
                    'mobile_no' => $userEmployeeData->personal_mobile,
                    'email' => $userEmployeeData->personal_email,
                    'ministry' => $userOfficeInfo[0]->office_ministry_id,
                    'signature' => null,
                    'profile_image' => null,
                    'role_id' => $organoGramUserInfo->user_role,
                    'office_id' => $userOfficeInfo[0]->office_id,
                    'is_gov' => 1,
                    'password' => Hash::make('!(MHL@9865@MMR#CSMS@)'),
                ];

                User::updateOrInsert(
                    ['doptor_user_id' => $userInfo->id],
                    $userData
                );

                $user = User::where('doptor_user_id', $userInfo->id)->first();
                Auth::loginUsingId($user->id);
                return redirect()->route('dashboard');
            }

            else if ($id && (!$organoGramUserInfo || !$organoGramUserInfo->user_role)) {
                $userInfo = $response->data->user;
                $userEmployeeData = $response->data->employee_info;
                $userOfficeInfo = $response->data->office_info;

                $userData = [
                    'name' => $userEmployeeData->name_bng,
                    'username' => $userInfo->user_alias,
                    'mobile_no' => $userEmployeeData->personal_mobile,
                    'email' => $userEmployeeData->personal_email,
                    'ministry' => $userOfficeInfo[0]->office_ministry_id,
                    'signature' => null,
                    'profile_image' => null,
                    'role_id' => 43,
                    'office_id' => $userOfficeInfo[0]->office_id,
                    'is_gov' => 1,
                    'password' => Hash::make('!(MHL@9865@MMR#CSMS@)'),
                ];

                User::updateOrInsert(
                    ['doptor_user_id' => $userInfo->id],
                    $userData
                );

                // Retrieve the user after update/insert
                $user = User::where('doptor_user_id', $userInfo->id)->first();
                Auth::loginUsingId($user->id);

                return redirect()->route('dashboard');
            }
        }
    }

    // public function initiateSSOLoginURL()
    // {
    //     // $ssoLoginUrl = 'https://n-doptor-accounts-stage.nothi.gov.bd/login';
    //     $ssoLoginUrl = 'https://api-training.doptor.gov.bd/v2/login';
    //     $clientAppUrl = 'https://api-training.doptor.gov.bd/v2/';
    //     $base64ClientAppUrl = base64_encode($clientAppUrl);

    //     return redirect()->to("{$ssoLoginUrl}?referer={$base64ClientAppUrl}");

    // }

    // public function verifyUser(Request $request)
    // {
    //     $username = $request->email;
    //     $password = $request->password;
    //     $userToken = $this->tokenGenerate($username);
    //     // return $userToken;
    //     $curl = curl_init();
    //     $apiUrl = 'https://apigw-stage.doptor.gov.bd/api/user/verify';
    //     $postData = json_encode(['username' => $username, 'password' => $password]);
    //     // return $postData;
    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => $apiUrl,
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => '',
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => 'POST',
    //         CURLOPT_POSTFIELDS => $postData,
    //         CURLOPT_HTTPHEADER => array(
    //             'Accept: application/json',
    //             'Content-Type: application/json',
    //             'api-version: 1',
    //             'apikey: 8XI1PI',
    //             'Authorization: ' . $userToken,
    //         ),
    //     ));

    //     $response = curl_exec($curl);

    //     if (curl_errno($curl)) {
    //         $errorResponse = json_encode(["status" => "error", "message" => "cURL error: " . curl_error($curl)]);
    //         return response()->json($errorResponse, 500);
    //     }

    //     curl_close($curl);
    //     $responsData = json_decode($response);

    //     return $responsData;
    // }
    // public function tokenGenerate($user_id)
    // {

    //     $curl = curl_init();
    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => 'https://apigw-stage.doptor.gov.bd/api/client/login',
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => '',
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => 'POST',
    //         CURLOPT_POSTFIELDS => array('username' => $user_id, 'password' => '8XI1PI12W', 'client_id' => '8XI1PI'),
    //         CURLOPT_HTTPHEADER => array(
    //             'apiKey: 8XI1PI  ',
    //         ),
    //     ));

    //     $response = curl_exec($curl);

    //     curl_close($curl);

    //     $responsData = json_decode($response);
    //     // dd($responsData);
    //     return $responsData->data->token;

    // }

    public static function logout_doptor()
    {
        // dd(1);+
        $callbackurl = url('/');
        $zoom_join_url = env('LOGIN_API2') . '/v2/logout?' . 'referer=' . base64_encode($callbackurl);

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
}
