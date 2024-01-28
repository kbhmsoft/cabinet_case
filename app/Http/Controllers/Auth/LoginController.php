<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
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

    public function doptorLogin(Request $request)
    {

        $username = $request->username;
        $password = $request->password;

        if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            // return response()->json(['success' => 'Successfully logged in!']);
            $success['user_id'] = $user->id;
            return $this->sendResponse($success, 'User login successfully.');
        }
        //  elseif (!empty($username) || !empty($password)) {

        //     $verifyUser = $this->verifyUser($request);
        //     // $data = json_decode($verifyUser);
        //     // return $verifyUser;
        //     $officeId = $verifyUser->data->office_info[0]->office_id;
        //     // dd($officeId);
        //     // $curl = curl_init();

        //     // Set API endpoint and request details
        //     // $apiUrl = 'https://apigw-stage.doptor.gov.bd/api/user/verify';
        //     // $postData = json_encode(['username' => $username, 'password' => $password]);

        //     // curl_setopt_array($curl, array(
        //     //     CURLOPT_URL => $apiUrl,
        //     //     CURLOPT_RETURNTRANSFER => true,
        //     //     CURLOPT_ENCODING => '',
        //     //     CURLOPT_MAXREDIRS => 10,
        //     //     CURLOPT_TIMEOUT => 0,
        //     //     CURLOPT_FOLLOWLOCATION => true,
        //     //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     //     CURLOPT_POSTFIELDS => $postData,
        //     //     CURLOPT_HTTPHEADER => array(
        //     //         'Accept: application/json',
        //     //         'Content-Type: application/json',
        //     //         'api-version: 1',
        //     //         'apikey: 8XI1PI',
        //     //         'Authorization: ' . $userToken,
        //     //     ),
        //     // ));

        //     // $response = curl_exec($curl);
        //     // // Check for cURL errors
        //     // if (curl_errno($curl)) {
        //     //     $errorResponse = json_encode(["status" => "error", "message" => "cURL error: " . curl_error($curl)]);
        //     //     return response()->json($errorResponse, 500);
        //     // }

        //     // curl_close($curl);
        //     // $responsData = json_decode($response);

        //     // return $responsData;
        // } else {
        //     return $this->sendError('Unauthorised.', ['error' => 'User login failed.'], 401);
        // }
    }

    public function verifyUser(Request $request)
    {

        $username = $request->username;
        $password = $request->password;
        $userToken = $this->tokenGenerate($username);
        $curl = curl_init();
        // Set API endpoint and request details
        $apiUrl = 'https://apigw-stage.doptor.gov.bd/api/user/verify';
        $postData = json_encode(['username' => $username, 'password' => $password]);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'api-version: 1',
                'apikey: 8XI1PI',
                'Authorization: ' . $userToken,
            ),
        ));

        $response = curl_exec($curl);
        // Check for cURL errors
        if (curl_errno($curl)) {
            $errorResponse = json_encode(["status" => "error", "message" => "cURL error: " . curl_error($curl)]);
            return response()->json($errorResponse, 500);
        }

        curl_close($curl);
        $responsData = json_decode($response);

        return $responsData;
    }
    public function tokenGenerate($user_id)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apigw-stage.doptor.gov.bd/api/client/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('username' => $user_id, 'password' => '8XI1PI12W', 'client_id' => '8XI1PI'),
            CURLOPT_HTTPHEADER => array(
                'apiKey: 8XI1PI  ',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $responsData = json_decode($response);
        // dd($responsData->data->token);
        return $responsData->data->token;

    }
}
