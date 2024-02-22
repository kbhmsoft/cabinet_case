<?php

// namespace App\Http\Controllers;
// use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use App\Http\Controllers\CommonController;
use App\Models\gov_case\GovCaseActivityLog;
use App\Models\User;


if (!function_exists('user_office_info')) {
	function user_office_info() {
		$user = Auth::user();
		return DB::table('users')->select('users.id AS user_id','division.id AS division_id', 'division.division_name_bn', 'district.id  AS district_id', 'district.district_name_bn', 'upazila.id  AS upazila_id', 'upazila.upazila_name_bn', 'office.office_name_bn', 'office.id AS office_id')
		->leftJoin('office', 'users.office_id', '=', 'office.id')
		->leftJoin('division', 'office.division_id', '=', 'division.id')
		->leftJoin('district', 'office.district_id', '=', 'district.id')
		->leftJoin('upazila', 'office.upazila_id', '=', 'upazila.id')
		->where('users.id', $user->id)
		->first();
	}
}

if (!function_exists('userInfo')) {
	function userInfo() {
		$user = Auth::user();
        return $user;
	}
}

if (!function_exists('user_division')) {
	function user_division() {
		$user = Auth::user();
		return DB::table('users')->select('division.id', 'division.division_name_bn')
		->leftJoin('office', 'users.office_id', '=', 'office.id')
		->join('division', 'office.division_id', '=', 'division.id')
		->where('users.id', $user->id)
		->first()->id;
	}
}

if (!function_exists('user_district')) {
	function user_district() {
		$user = Auth::user();
		return $district =  DB::table('office')->select('district_id')
		->join('district', 'office.district_id', '=', 'district.id')
		->where('office.id',$user->office_id)
		->first()->district_id;
	}
}

if (!function_exists('user_upazila')) {
	function user_upazila() {
		$user = Auth::user();
		return $upazila =  DB::table('office')->select('upazila_id')
		->join('upazila', 'office.upazila_id', '=', 'upazila.id')
		->where('office.id',$user->office_id)
		->first()->upazila_id;
	}
}

if (!function_exists('user_email')) {
	function user_email() {
		$user = Auth::user();
		return $user->email;
	}
}

if (!function_exists('en2bn')) {
	function en2bn($item) {
		return App\Http\Controllers\CommonController::en2bn($item);
		// echo $item;
	}
}
if (!function_exists('bn2en')) {
	function bn2en($item) {
		return App\Http\Controllers\CommonController::bn2en($item);
		// echo $item;
	}
}

if (!function_exists('case_status')) {
	function case_status($item) {
		if($item == 1){
			$result = "<span class='label label-success'>Enable</span>";
		}else{
			$result = "<span class='label label-warning'>Disable</span>";
		}
		return $result;
	}
}


if (!function_exists('gov_case_activity_logs')) {
	function gov_case_activity_logs($data) {

        $user = Auth::user();
        $userOffice = user_office_info();

        $log = new GovCaseActivityLog;
        $log->user_id = $user->id;
        $log->gov_case_id = $data['case_register_id'];
        $log->user_roll_id = $user->role_id;
        $log->activity_type = $data['activity_type'];
        $log->message = $data['message'];
        $log->office_id = $user->office_id;
        $log->old_data = $data['old_data'];
        $log->new_data = $data['new_data'];
        $log->ip_address = request()->ip();
        $log->user_agent = request()->userAgent();

        $log->save();
        return $log;
	}

    if(!function_exists('DOPTOR_ENDPOINT')){
        function DOPTOR_ENDPOINT()
        {
        //    return "https://api-training.doptor.gov.bd";
          return "https://n-doptor-api.nothi.gov.bd";
        }
    }
    // if(!function_exists('DOPTOR_OFFICE_ORGANOGRAM')){
    //     function DOPTOR_OFFICE_ORGANOGRAM()
    //     {
    //     //    return "https://api-training.doptor.gov.bd";
    //        return "https://n-doptor-api.nothi.gov.bd";
    //     }
    // }
}   

