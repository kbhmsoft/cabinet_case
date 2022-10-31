<?php
/**
 * Created by PhpStorm.
 * User: destructor
 * Date: 11/29/2017
 * Time: 9:51 PM
 */
namespace App\Repositories\gov_case;

use App\Models\gov_case\GovCaseBadi;
use App\Models\gov_case\GovCaseBibadi;
use App\Models\gov_case\GovCaseLog;

class GovCaseLogRepository
{
    public static function storeGovCaseLog($govCaseId){
        $log_data = [
            'gov_case_id'       => $govCaseId,
            'case_status_id'     => 33,
            'user_id'       => userInfo()->id,
            // 'sender_user_role_id' => $user->role_id,
            'receiver_user_role_id' => userInfo()->role_id,
            // 'comments'       => $request->comment,
        ];
        GovCaseLog::insert($log_data);
    }

    public static function storeFowardGovCaseLog($request){
        $log_data = [
            'gov_case_id' => $request->case_id,
            'case_status_id' => $request->status_id,
            'user_id' => userInfo()->id,
            'sender_user_role_id' => userInfo()->role_id,
            'receiver_user_role_id' => $request->group,
            'comments' => $request->comment,
        ];
        GovCaseLog::insert($log_data);
    }

    public static function getCaseLogByCaseId($caseId){
        $caseLog=GovCaseLog::with('case_status')->where('gov_case_id', $caseId)->get();
        return $caseLog;
    }
}
