<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends BaseController
{
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $roleName = DB::table('roles')->select('name')->where('id', $user->role_id)
                ->first()->name;
            // Office name //
            $officeInfo = DB::table('office')->select('office_name_bn', 'division_id', 'district_id', 'upazila_id')->where('id', $user->office_id)
                ->first();
            // Results
            $success['user_id'] = $user->id;
            $success['name'] = $user->name;
            $success['email'] = $user->email;
            $success['profile_pic'] = $user->profile_pic;
            $success['role_id'] = $user->role_id;
            $success['name'] = $roleName;
            $success['office_id'] = $user->office_id;
            $success['office_name'] = $officeInfo->office_name_bn;
            $success['division_id'] = $officeInfo->division_id;
            $success['district_id'] = $officeInfo->district_id;
            $success['upazila_id'] = $officeInfo->upazila_id;
            $success['token'] = $user->createToken('Login')->accessToken;

            return $this->sendResponse($success, 'User login successfully.');
        } else {

            return $this->sendError('Unauthorised.', ['error' => 'User login failed.'], 401);
        }
    }

    public function doptorLogin(Request $request)
    {

        $username = $request->username;
        $password = $request->password;

        if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();

            $roleName = DB::table('roles')->select('name')->where('id', $user->role_id)
                ->first()->name;
            // Office name //
            $officeInfo = DB::table('office')->select('office_name_bn', 'division_id', 'district_id', 'upazila_id')->where('id', $user->office_id)
                ->first();
            // Results
            dd($officeInfo);
            $success['user_id'] = $user->id;
            $success['name'] = $user->name;
            $success['email'] = $user->email;
            $success['profile_pic'] = $user->profile_pic;
            $success['role_id'] = $user->role_id;
            $success['name'] = $roleName;
            $success['office_id'] = $user->office_id;
            $success['office_name'] = $officeInfo->office_name_bn;
            $success['division_id'] = $officeInfo->division_id;
            $success['district_id'] = $officeInfo->district_id;
            $success['upazila_id'] = $officeInfo->upazila_id;
            $success['token'] = $user->createToken('Login')->accessToken;

            return $this->sendResponse($success, 'User login successfully.');
        } elseif (empty($username) || empty($password)) {
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
        } else {

            return $this->sendError('Unauthorised.', ['error' => 'User login failed.'], 401);
        }

        // Check if username or password is empty
        // if (empty($username) || empty($password)) {
        //     $errorResponse = json_encode(["status" => "error", "message" => "Username or password empty."]);
        //     return response()->json($errorResponse, 400);
        // }

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

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    /*
public function register(Request $request)
{
$validator = Validator::make($request->all(), [
'name' => 'required',
'email' => 'required|email',
'password' => 'required',
'c_password' => 'required|same:password',
]);

if($validator->fails()){
return $this->sendError('Validation Error.', $validator->errors());
}

$input = $request->all();
$input['password'] = bcrypt($input['password']);
$user = User::create($input);
$success['token'] =  $user->createToken('MyApp')->accessToken;
$success['name'] =  $user->name;

return $this->sendResponse($success, 'User register successfully.');
}
 */
}
