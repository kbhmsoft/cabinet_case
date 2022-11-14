<?php
/**
 * Created by PhpStorm.
 * User: destructor
 * Date: 11/29/2017
 * Time: 9:51 PM
 */
namespace App\Repositories\gov_case;

use App\Models\Attachment;
use App\Models\gov_case\GovCaseBadi;
use App\Models\gov_case\GovCaseRegister;
use App\Models\gov_case\GovCaseHearing;
use Illuminate\Support\Facades\DB;

class GovCaseRegisterRepository
{
    public static function GovCaseAllDetails($caseId){
        $case = GovCaseRegister::findOrFail($caseId);
        $caseBadi = GovCaseBadiBibadiRepository::getBadiByCaseId($caseId);
        $caseBibadi = GovCaseBadiBibadiRepository::getBibadiByCaseId($caseId);
        $caseLog = GovCaseLogRepository::getCaseLogByCaseId($caseId);
        $hearings = GovCaseHearing::where('gov_case_id',$caseId)->get();
        $files = Attachment::where('gov_case_id',$caseId)->get();

        $data = [
            'case'  => $case,
            'caseBadi'  => $caseBadi,
            'caseBibadi'  => $caseBibadi,
            'caseLogs'  => $caseLog,
            'hearings'  => $hearings,
            'files'  => $files,
        ];

        return $data;
    }
    public static function storeGovCase($caseInfo){
        $case = self::checkGovCaseExist($caseInfo['caseId']);
        $ref_case_num = null;
        if ($caseInfo->appeal_case_id != NULL || $caseInfo->appeal_case_id != '') {
            $ref_case_num = DB::table('gov_case_registers')->select('case_no')->where('id', $caseInfo->appeal_case_id)->first()->case_no;
        }
        // dd($ref_case_num);

        try {
            $case->case_no=$caseInfo->case_no;
            $case->case_type=$caseInfo->case_type;
            $case->court_id= $caseInfo->court;
            $case->action_user_id= userInfo()->id;
            $case->action_user_role_id= userInfo()->role_id;
            $case->create_by= userInfo()->id;
            $case->year= $caseInfo->case_year;
            $case->date_issuing_rule_nishi =date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->case_date)));
            $case->case_division_id= $caseInfo->court;
            $case->case_category_id= $caseInfo->case_category;
            $case->concern_user_id= $caseInfo->concern_person;
            $case->subject_matter= $caseInfo->subject_matter;
            $case->postponed_details= $caseInfo->postponed_details;
            $case->important_cause= $caseInfo->important_cause;
            $case->interim_order= $caseInfo->interim_order;
            $case->gov_case_ref_id= $caseInfo->appeal_case_id;
            $case->ref_gov_case_no= $ref_case_num;
            $case->result_sending_date= date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->result_sending_date)));
            $case->reply_submission_date=date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->reply_submission_date)));  
            $case->result_short_dtails= $caseInfo->result_short_dtails;
            $case->result= $caseInfo->result;
            $case->is_appeal= $caseInfo->is_appeal;
            $case->arji_file= null;
            $case->status= 1;
            $case->case_status_id= 33;
            if($case->save()){
                $caseId=$case->id;
            }
        } catch (\Exception $e) {
            dd($e);
            $caseId=null;
        }
        return $caseId;
    }

    
    public static function storeAppealGovCase($caseInfo,$id){
        $case = self::checkGovCaseExist($caseInfo['caseId']);
        $oldcase = self::checkGovCaseExist($id);
        // dd($oldcase->case_no);

        try {
            $case->case_no=$caseInfo->case_no;;
            $case->court_id= $caseInfo->court;
            $case->action_user_id= userInfo()->id;
            $case->action_user_role_id= userInfo()->role_id;
            $case->create_by= userInfo()->id;
            $case->year= $caseInfo->case_year;
            $case->date_issuing_rule_nishi =date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->case_date)));
            $case->case_division_id= $caseInfo->case_department;
            $case->case_category_id= $caseInfo->case_category;
            $case->concern_user_id= $caseInfo->concern_person;
            $case->subject_matter= $caseInfo->subject_matter;
            $case->postponed_details= $caseInfo->postponed_details;
            $case->interim_order= $caseInfo->interim_order;
            $case->important_cause= $caseInfo->important_cause;
            $case->arji_file= null;
            $case->status= 1;
            $case->gov_case_ref_id= $id;
            $case->ref_gov_case_no= $oldcase->case_no;
            $case->case_status_id= 43;
            if($case->save()){
                $caseId=$case->id;
                $oldcase->is_appeal= 1;
                $oldcase->save();
            }
        } catch (\Exception $e) {
            dd($e);
            $caseId=null;
        }
        return $caseId;
    }

    public static function checkGovCaseExist($caseId){
        if($caseId != null){
            $case=GovCaseRegister::find($caseId);
        }else{
            $case=new GovCaseRegister();
        }
        return $case;
    }

    public static function updateGovCaseAsFoward($request){
        $case_data = [
        'case_status_id'     => $request->status_id,
        'action_user_role_id' => $request->group,
        ];
        GovCaseRegister::whereId($request->case_id)->update($case_data);
    }
    public static function caseStatusByRoleId($roleID){
        $office = userInfo()->office_id;
        $case_status = GovCaseRegister::select('case_status_id', DB::raw('COUNT(id) as total_case'))
            ->where('action_user_role_id', $roleID)
            // ->whereHas('bibadis', function($query) use ($office){
            //     // $query->where('ministry_id', $office);
            //     // $query->where('department_id', $office);
            // })
            ->groupBy('case_status_id')
            ->get();

            // 'ministry_id',
            // 'department_id',
            // dd($case_status);
        return $case_status;
    }
    public static function caseStatusByRoleIds($roleID=[]){
        // dd($roleID);
        $office = userInfo()->office_id;
        $case_status = GovCaseRegister::select('case_status_id', DB::raw('COUNT(id) as total_case'))
            ->whereIn('action_user_role_id', $roleID)
            // ->whereHas('bibadis', function($query) use ($office){
            //     // $query->where('ministry_id', $office);
            //     // $query->where('department_id', $office);
            // })
            ->groupBy('case_status_id')
            ->get();

            // 'ministry_id',
            // 'department_id',
            // dd($case_status);
        return $case_status;
    }
}
