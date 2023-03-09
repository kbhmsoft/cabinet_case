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
        $caseMainBibadi = GovCaseBadiBibadiRepository::getMainBibadiByCaseId($caseId);
        $caseLog = GovCaseLogRepository::getCaseLogByCaseId($caseId);
        $hearings = GovCaseHearing::where('gov_case_id',$caseId)->get();
        $files = Attachment::where('gov_case_id',$caseId)->get();

        $data = [
            'case'  => $case,
            'caseBadi'  => $caseBadi,
            'caseMainBibadi'  => $caseMainBibadi,
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
        if ($caseInfo->appeal_case_id != NULL && $caseInfo->appeal_case_id != '') {
            $ref_case_num = DB::table('gov_case_registers')->select('case_no')->where('id', $caseInfo->appeal_case_id)->first()->case_no;
        }

        if ($caseInfo->result_sending_date != NULL && $caseInfo->result_sending_date != '') {
            $result_sending_date = date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->result_sending_date)));        
        } else {
            $result_sending_date = null;
        }
        if ($caseInfo->reply_submission_date != NULL && $caseInfo->reply_submission_date != '') {
            $reply_submission_date = date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->reply_submission_date)));        
        }
        if ($caseInfo->appeal_requesting_date != NULL && $caseInfo->appeal_requesting_date != '') {
            $appeal_requesting_date = date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->appeal_requesting_date)));        
        } else {
            $appeal_requesting_date = null;
        }
        if ($caseInfo->contempt_case_isuue_date != NULL && $caseInfo->contempt_case_isuue_date != '') {
            $contempt_case_isuue_date = date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->contempt_case_isuue_date)));        
        } else {
            $contempt_case_isuue_date = null;
        }
        if ($caseInfo->contempt_case_answer_sending_date != NULL && $caseInfo->contempt_case_answer_sending_date != '') {
            $contempt_case_answer_sending_date = date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->contempt_case_answer_sending_date)));        
        } else {
            $contempt_case_answer_sending_date = null;
        }
        if ($caseInfo->leave_to_appeal_date != NULL && $caseInfo->leave_to_appeal_date != '') {
            $leave_to_appeal_date = date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->leave_to_appeal_date)));        
        } else {
            $leave_to_appeal_date = null;
        }
        if ($caseInfo->leave_to_appeal_order_date != NULL && $caseInfo->leave_to_appeal_order_date != '') {
            $leave_to_appeal_order_date = date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->leave_to_appeal_order_date)));        
        } else {
            $leave_to_appeal_order_date = null;
        }
        if ($caseInfo->review_case_date != NULL && $caseInfo->review_case_date != '') {
            $review_case_date = date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->review_case_date)));        
        } else {
            $review_case_date = null;
        }
        if ($caseInfo->review_case_order_date != NULL && $caseInfo->review_case_order_date != '') {
            $review_case_order_date = date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->review_case_order_date)));        
        } else {
            $review_case_order_date = null;
        }
        if ($caseInfo->civil_appeal_order_date != NULL && $caseInfo->civil_appeal_order_date != '') {
            $civil_appeal_order_date = date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->civil_appeal_order_date)));        
        } else {
            $civil_appeal_order_date = null;
        }
        if ($caseInfo->result_sending_date_solisitor_to_ag != NULL && $caseInfo->result_sending_date_solisitor_to_ag != '') {
            $result_sending_date_solisitor_to_ag = date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->result_sending_date_solisitor_to_ag)));        
        } else {
            $result_sending_date_solisitor_to_ag = null;
        }
        if ($caseInfo->tamil_requesting_date != NULL && $caseInfo->tamil_requesting_date != '') {
            $tamil_requesting_date = date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->tamil_requesting_date)));        
        } else {
            $tamil_requesting_date = null;
        }
        if ($caseInfo->appeal_against_postpond_interim_order_date != NULL && $caseInfo->appeal_against_postpond_interim_order_date != '') {
            $appeal_against_postpond_interim_order_date = date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->appeal_against_postpond_interim_order_date)));        
        } else {
            $appeal_against_postpond_interim_order_date = null;
        }
        if ($caseInfo->result_date != NULL && $caseInfo->result_date != '') {
            $result_date = date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->result_date)));        
        } else {
            $result_date = null;
        }
        if ($caseInfo->result_copy_asking_date != NULL && $caseInfo->result_copy_asking_date != '') {
            $result_copy_asking_date = date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->result_copy_asking_date)));        
        } else {
            $result_copy_asking_date = null;
        }
        if ($caseInfo->result_copy_reciving_date != NULL && $caseInfo->result_copy_reciving_date != '') {
            $result_copy_reciving_date = date('Y-m-d',strtotime(str_replace('/', '-', $caseInfo->result_copy_reciving_date)));        
        } else {
            $result_copy_reciving_date = null;
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
            $case->case_type_id= $caseInfo->case_category_type;
            $case->concern_user_id= $caseInfo->concern_person;
            $case->subject_matter= $caseInfo->subject_matter;
            $case->postponed_order= $caseInfo->postponed_order;
            $case->postponed_details= $caseInfo->postponed_details;
            $case->important_cause= $caseInfo->important_cause;
            $case->interim_order= $caseInfo->interim_order;
            $case->interim_order_details= $caseInfo->interim_order_details;
            $case->gov_case_ref_id= $caseInfo->appeal_case_id;
            $case->ref_gov_case_no= $ref_case_num;
            $case->result_sending_date = $result_sending_date;
            $case->result_sending_memorial= $caseInfo->result_sending_memorial;
            $case->result_sending_date_solisitor_to_ag= $result_sending_date_solisitor_to_ag;
            $case->result_sending_memorial_solisitor_to_ag= $caseInfo->result_sending_memorial_solisitor_to_ag;
            $case->result_sending_memorial= $caseInfo->result_sending_memorial;
            $case->reply_submission_date = $reply_submission_date;  
            $case->result_short_dtails= $caseInfo->result_short_dtails;
            $case->result= $caseInfo->result;
            $case->is_appeal= $caseInfo->is_appeal;
            $case->comments= $caseInfo->comments;
            $case->arji_file= null;
            $case->status= 1;
            $case->case_status_id= 33;
            $case->appeal_requesting_memorial= $caseInfo->appeal_requesting_memorial;
            $case->reason_of_not_appealing= $caseInfo->reason_of_not_appealing;
            $case->contempt_case_no= $caseInfo->contempt_case_no;
            $case->contempt_case_order= $caseInfo->contempt_case_order;
            $case->appeal_requesting_date = $appeal_requesting_date;  
            $case->contempt_case_isuue_date = $contempt_case_isuue_date;  
            $case->contempt_case_answer_sending_date = $contempt_case_answer_sending_date;  
            $case->leave_to_appeal_no= $caseInfo->leave_to_appeal_no;
            $case->leave_to_appeal_date = $leave_to_appeal_date;  
            $case->leave_to_appeal_order_date = $leave_to_appeal_order_date;  
            $case->leave_to_appeal_order_details= $caseInfo->leave_to_appeal_order_details;  
            $case->review_case_no= $caseInfo->review_case_no;
            $case->review_case_date = $review_case_date;  
            $case->review_case_order_date = $review_case_order_date;  
            $case->review_case_order_details= $caseInfo->review_case_order_details;
            $case->civil_appeal_order_date = $civil_appeal_order_date;  
            $case->civil_appeal_order_details= $caseInfo->civil_appeal_order_details;
            $case->tamil_requesting_memorial= $caseInfo->tamil_requesting_memorial;
            $case->tamil_requesting_date= $tamil_requesting_date;
            $case->appeal_against_postpond_interim_order= $caseInfo->appeal_against_postpond_interim_order;
            $case->appeal_against_postpond_interim_order_date= $appeal_against_postpond_interim_order_date;
            $case->appeal_against_postpond_interim_order_details= $caseInfo->appeal_against_postpond_interim_order_details;
            $case->result_date= $result_date;
            $case->result_copy_asking_date= $result_copy_asking_date;
            $case->result_copy_reciving_date= $result_copy_reciving_date;
            $case->others_action_detials= $caseInfo->others_action_detials;
            if($case->save()){
                $caseId=$case->id;
                if ($caseInfo->appeal_case_id != NULL && $caseInfo->appeal_case_id != '') {
                    self::prevCaseStatusUpdate($caseInfo->appeal_case_id);
                }
            }
        } catch (\Exception $e) {
            dd($e);
            $caseId=null;
        }
        return $caseId;
    }
    public function prevCaseStatusUpdate($prevCaseId)
    {
        $data = [
            'is_appeal'      => 1,
        ];
     $updatedVal = DB::table('gov_case_registers')
        ->where('id', $prevCaseId)
        ->update($data);
        return $updatedVal;
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
        if($request->main_min_id){
            $case_data = [
                'case_status_id'     => $request->status_id,
                'action_user_role_id' => $request->group,
                'selected_main_min_id' => $request->main_min_id,
            ];
        }elseif ($request->main_dept_id) {
            $case_data = [
                'case_status_id'     => $request->status_id,
                'action_user_role_id' => $request->group,
                'selected_main_dept_id' => $request->main_dept_id,
            ];
        }elseif ($request->group == 36) {
            $case_data = [
                'case_status_id'     => $request->status_id,
                'action_user_role_id' => $request->group,
                'ag_office_sending_date' => date("Y-m-d"),
            ];
        }else{
            $case_data = [
                'case_status_id'     => $request->status_id,
                'action_user_role_id' => $request->group,
            ];
        }
        GovCaseRegister::whereId($request->case_id)->update($case_data);
    }
    public static function caseStatusByRoleId($roleID){
        $roleID = userInfo()->role_id;
        $office = userInfo()->office_id;
        $query = GovCaseRegister::select('case_status_id', DB::raw('COUNT(id) as total_case'))->where('status','!=', 3)->where('action_user_role_id', $roleID);
        if($roleID == 29 || $roleID == 31){
            $query->where('selected_main_min_id', $office);
        }elseif($roleID == 32 || $roleID == 33){
            $query->where('selected_main_dept_id', $office);
        }

        $case_status = $query->groupBy('case_status_id')->get();

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
