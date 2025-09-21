<?php

namespace App\Repositories\gov_case;

use App\Models\Role;
use App\Models\User;
use App\Models\Attachment;
use App\Models\AppealAttachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\gov_case\GovCaseHearing;
use App\Models\gov_case\GovCaseRegister;
use App\Models\gov_case\AppealOrderTaken;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Models\gov_case\GovCaseAppealAdalat;
use App\Models\gov_case\AppealGovCaseRegister;
use App\Models\gov_case\AppealGovCaseOrderTaken;
use App\Models\gov_case\AppealGovCaseConcernPerson;

class AppealGovCaseRegisterRepository
{
    public static function AppealGovCaseAllDetails($caseId)
    {

        $case = AppealGovCaseRegister::findOrFail($caseId);
        $caseBadi = GovCaseBadiBibadiRepository::getBadiByCaseId($caseId);
        $appealCaseLawers = GovCaseBadiBibadiRepository::getAppealConcernPersonByCaseId($caseId);

        $caseBibadi = GovCaseBadiBibadiRepository::getBibadiByCaseId($caseId);
        $mainBibadi = GovCaseBadiBibadiRepository::getMainBibadiByCaseId($caseId);
        $otherBibadi = GovCaseBadiBibadiRepository::getOthersBibadiByCaseId($caseId);
        $caseMainBibadi = GovCaseBadiBibadiRepository::getMainBibadiByCaseId($caseId);


        $concernpersondesig = Role::where('id', $case->concern_person_designation)->first();
        $concernPersonName = User::where('id', $case->concern_user_id)->first();

        $data = [
            'case' => $case,
            'caseBadi' => $caseBadi,
            'caseLawers' => $appealCaseLawers,

            'caseMainBibadi' => $caseMainBibadi,
            'caseBibadi' => $caseBibadi,
            'mainBibadi' => $mainBibadi,
            'otherBibadi' => $otherBibadi,

            'concernpersondesig' => $concernpersondesig,
            'concernPersonName' => $concernPersonName,
        ];

        return $data;
    }



    public static function AppealMainRespondentGovCaseAllDetails($caseId)
    {

        // $case = AppealGovCaseRegister::findOrFail($caseId);
        // $caseBadi = GovCaseBadiBibadiRepository::getBadiByCaseId($caseId);
        $appealCaseLawers = GovCaseBadiBibadiRepository::getAppealConcernPersonByCaseId($caseId->id);

        // $caseBibadi = GovCaseBadiBibadiRepository::getBibadiByCaseId($caseId);
        // $mainBibadi = GovCaseBadiBibadiRepository::getMainBibadiByCaseId($caseId);
        // $otherBibadi = GovCaseBadiBibadiRepository::getOthersBibadiByCaseId($caseId);
        // $caseMainBibadi = GovCaseBadiBibadiRepository::getMainBibadiByCaseId($caseId);


        $concernpersondesig = Role::where('id', $caseId->concern_person_designation)->first();
        $concernPersonName = User::where('id', $caseId->concern_user_id)->first();

        $data = [
            // 'appealCase' => $case,
            // 'caseBadi' => $caseBadi,
            'caseLawers' => $appealCaseLawers,

            // 'caseMainBibadi' => $caseMainBibadi,
            // 'caseBibadi' => $caseBibadi,
            // 'mainBibadi' => $mainBibadi,
            // 'otherBibadi' => $otherBibadi,

            'concernpersondesig' => $concernpersondesig,
            'concernPersonName' => $concernPersonName,
        ];

        return $data;
    }




    public static function AppealCaseAllDetails($caseId)
    {
        $case = AppealGovCaseRegister::findOrFail($caseId);
        // $caseLog = GovCaseLogRepository::getCaseLogByCaseId($caseId);
        // $hearings = GovCaseHearing::where('gov_case_id', $caseId)->get();
        $files = AppealAttachment::where('appeal_gov_case_id', $caseId)->get();
        $concernpersondesig = Role::where('id', $case->concern_person_designation)->first();
        $concernPersonName = User::where('id', $case->concern_user_id)->first();
        $caseNumberOrigin = GovCaseRegister::where('case_no', $case->case_number_origin)->first();
        // return $caseNumberOrigin;
        $data = [
            'appealCase' => $case,
            'caseNumberOrigin' => $caseNumberOrigin,
            'files' => $files,
            'concernpersondesig' => $concernpersondesig,
            'concernPersonName' => $concernPersonName,
        ];

        return $data;
    }

    public static function storeAppeal($caseInfo)
    {
        $case = self::checkAppealGovCaseExist($caseInfo['caseId']);

        $petitioner_name = '';
        if ($caseInfo->appeal_office == 0) {
            $petitioner_name = $caseInfo->appeal_petitioner_name;
        }
        try {
            $case->case_no = $caseInfo->case_no;
            $case->case_category_id = $caseInfo->case_category;
            $case->case_type_id = $caseInfo->case_category_type;
            $case->year = $caseInfo->case_year;
            $case->case_origin_year = $caseInfo->case_origin_year;
            $case->appeal_petitioner_name = $petitioner_name;
            $case->appeal_office_id = $caseInfo->appeal_office;
            $case->created_by = Auth::user()->id;
            $case->created_by_office = Auth::user()->office_id;

            $case->case_division_id = 1;

            if (empty($caseInfo->case_entry_date)) {
                $case->case_entry_date = date('Y-m-d');
            } else {
                // If $caseInfo->case_entry_date is not empty, convert and assign the value
                $case->case_entry_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->case_entry_date)));
            }

            if (empty($caseInfo->postpond_date)) {
                $case->postpond_date = date('Y-m-d');
            } else {
                // If $caseInfo->postpond_date is not empty, convert and assign the value
                $case->postpond_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->postpond_date)));
            }
            $case->postponed_details = $caseInfo->postponed_details ?? '';

            if ($caseInfo->case_number_origin) {

                $case->case_category_origin = $caseInfo->case_category_origin;

                $case->case_number_origin = $caseInfo->case_number_origin;

                // $case->case_origin_id = $caseInfo->case_number_origin;
            } else {
                $case->case_number_origin = $caseInfo->case_number_origin_manual;
                $case->writ_petitioner_name = $caseInfo->writ_petitioner_name;
                $case->subject_matter = $caseInfo->subject_matter;
                $case->case_order_date = $caseInfo->case_order_date;
                $case->case_order_details = $caseInfo->case_order_details;
            }
            $case->is_appeal = 1;

            if ($case->save()) {
                $caseId = $case->id;
                if ($caseInfo->case_number_origin != null && $caseInfo->case_number_origin != '') {
                    self::prevCaseStatusUpdate($caseInfo->case_number_origin);
                }
            }
        } catch (\Exception $e) {
            dd($e);
            $caseId = null;
        }
        return $caseId;
    }


    public static function storeAppealMainRespondentChange($caseInfo)
    {
        $case = self::checkAppealGovCaseExist($caseInfo['caseId']);

        $petitioner_name = '';
        if ($caseInfo->appeal_office == 0) {
            $petitioner_name = $caseInfo->appeal_petitioner_name;
        }

        try {
            $case->case_no = $caseInfo->case_no;
            $case->case_category_id = $caseInfo->case_category;
            $case->case_type_id = $caseInfo->case_category_type;
            $case->year = $caseInfo->case_year;
            $case->appeal_petitioner_name = $petitioner_name;
            $case->appeal_office_id = $caseInfo->appeal_office;
            $case->created_by = Auth::user()->id;
            $case->created_by_office = $caseInfo->main_respondent ? $caseInfo->main_respondent : Auth::user()->office_id;

            $case->case_division_id = 1;

            if (empty($caseInfo->case_entry_date)) {
                $case->case_entry_date = date('Y-m-d'); // Set to current date
            } else {
                // If $caseInfo->case_entry_date is not empty, convert and assign the value
                $case->case_entry_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->case_entry_date)));
            }

            if (empty($caseInfo->postpond_date)) {
                $case->postpond_date = date('Y-m-d'); // Set to current date
            } else {
                // If $caseInfo->postpond_date is not empty, convert and assign the value
                $case->postpond_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->postpond_date)));
            }
            $case->postponed_details = $caseInfo->postponed_details ?? '';
            if ($caseInfo->case_number_origin) {

                $case->case_category_origin = $caseInfo->case_category_origin;

                $case->case_number_origin = $caseInfo->case_number_origin;

                $case->case_origin_id = $caseInfo->case_number_origin;
            } else {
                $case->case_number_origin = $caseInfo->case_number_origin_manual;
                $case->writ_petitioner_name = $caseInfo->writ_petitioner_name;
                $case->subject_matter = $caseInfo->subject_matter;
                $case->case_order_date = $caseInfo->case_order_date;
                $case->case_order_details = $caseInfo->case_order_details;
            }
            $case->is_appeal = 1;

            if ($case->save()) {
                $caseId = $case->id;
                if ($caseInfo->case_number_origin != null && $caseInfo->case_number_origin != '') {
                    self::prevCaseStatusUpdate($caseInfo->case_number_origin);
                }
            }
        } catch (\Exception $e) {
            dd($e);
            $caseId = null;
        }
        return $caseId;
    }


    public static function storeAppealAdalat($caseInfo, $govCaseId)
    {
        foreach ($caseInfo->appeal_adalat as $key => $val) {
            if ($caseInfo->appeal_adalat[$key] != null) {
                $appealAdalat = self::checkHighcourtAdalatExist($caseInfo->appeal_adalat_id[$key]);
                $appealAdalat->gov_case_id = $govCaseId;
                $appealAdalat->appeal_adalat = $caseInfo->appeal_adalat[$key];

                $appealAdalat->save();
            }
        }
    }

    public static function checkHighcourtAdalatExist($appealAdalatId)
    {
        if (isset($appealAdalatId)) {

            $appealAdalat = GovCaseAppealAdalat::find($appealAdalatId);
        } else {

            $appealAdalat = new GovCaseAppealAdalat();
        }

        return $appealAdalat;
    }

    // public static function storeConcernPerson($caseInfo, $govCaseId)
    // {
    //     // dd($caseInfo->concernPersonDesignation);
    //     if ($caseInfo->concernPersonDesignation) {
    //         foreach ($caseInfo->concernPersonDesignation as $key => $val) {
    //             if ($val !== null) {
    //                 if ($val === 'no_officer') {
    //                     continue;
    //                 }

    //                 $concernPrerson = self::checkConcernPersonExist($caseInfo->concern_user_id[$key]);
    //                 $concernPrerson->gov_case_id = $govCaseId;
    //                 $concernPrerson->concern_person_designation = $val;
    //                 $concernPrerson->concern_user_id = $caseInfo->concern_user_id[$key] ?? null; // Ensure null if not provided
    //                 $concernPrerson->save();
    //             }
    //         }
    //     }
    // }

    public static function storeConcernPerson($caseInfo, $govCaseId)
    {
        if (isset($caseInfo['concernPersonDesignation'])) {
            foreach ($caseInfo['concernPersonDesignation'] as $key => $val) {
                if ($val !== null) {
                    if ($val === 'no_officer') {
                        continue;
                    }
                    $concernPrerson = self::checkConcernPersonExist($caseInfo['concern_person_id'][$key]);
                    $concernPrerson->gov_case_id = $govCaseId;
                    $concernPrerson->concern_person_designation = $val;
                    $concernPrerson->concern_user_id = $caseInfo['concern_user_id'][$key] ?? null;

                    $concernPrerson->save();
                }
            }
        }
    }

    //store appeal final order

    public static function storeAppealFinalOrder($caseInfo)
    {
        $case = self::checkAppealGovCaseExist($caseInfo['case_id']);

        if ($caseInfo->result_date != null && $caseInfo->result_date != '') {
            $result_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->result_date)));
        } else {
            $result_date = null;
        }
        if ($caseInfo->result_copy_asking_date != null && $caseInfo->result_copy_asking_date != '') {
            $result_copy_asking_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->result_copy_asking_date)));
        } else {
            $result_copy_asking_date = null;
        }
        if ($caseInfo->result_copy_receiving_date != null && $caseInfo->result_copy_receiving_date != '') {
            $result_copy_receiving_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->result_copy_receiving_date)));
        } else {
            $result_copy_receiving_date = null;
        }
        if ($caseInfo->appeal_requesting_date != null && $caseInfo->appeal_requesting_date != '') {
            $appeal_requesting_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->appeal_requesting_date)));
        } else {
            $appeal_requesting_date = null;
        }
        if ($caseInfo->proposal_date_civil_revision != null && $caseInfo->proposal_date_civil_revision != '') {
            $proposal_date_civil_revision = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->proposal_date_civil_revision)));
        } else {
            $proposal_date_civil_revision = null;
        }
        if ($caseInfo->proposal_date_writ != null && $caseInfo->proposal_date_writ != '') {
            $proposal_date_writ = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->proposal_date_writ)));
        } else {
            $proposal_date_writ = null;
        }
        if ($caseInfo->result == 1) {
            $in_favour_govt = 1;
        } else {
            $in_favour_govt = 0;
        }

        try {
            $case->is_final_order = $caseInfo->is_final_order ?? '';
            $case->result = $caseInfo->result ?? '';
            $case->in_favour_govt = $in_favour_govt ?? '';
            $case->result_short_details = $caseInfo->result_short_details ?? '';
            // $case->is_appeal = $caseInfo->is_appeal;
            $case->result_date = $result_date;
            $case->result_copy_asking_date = $result_copy_asking_date ?? '';
            $case->result_copy_receiving_date = $result_copy_receiving_date ?? '';
            // $case->appeal_requesting_memorial = $caseInfo->appeal_requesting_memorial ?? '';
            // $case->appeal_requesting_date = $appeal_requesting_date ?? '';
            // $case->reason_of_not_appealing = $caseInfo->reason_of_not_appealing ?? '';

            if ($case->save()) {
                $caseId = $case->id;
            }
        } catch (\Exception $e) {
            dd($e);
            $caseId = null;
        }
        return $caseId;
    }

    public static function storeCompleteAppeal($caseInfo)
    {
        $case = self::checkAppealGovCaseExist($caseInfo['caseId']);

        if ($caseInfo->result_date != null && $caseInfo->result_date != '') {
            $result_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->result_date)));
        } else {
            $result_date = null;
        }
        if ($caseInfo->result_copy_asking_date != null && $caseInfo->result_copy_asking_date != '') {
            $result_copy_asking_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->result_copy_asking_date)));
        } else {
            $result_copy_asking_date = null;
        }
        if ($caseInfo->result_copy_receiving_date != null && $caseInfo->result_copy_receiving_date != '') {
            $result_copy_receiving_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->result_copy_receiving_date)));
        } else {
            $result_copy_receiving_date = null;
        }
        if ($caseInfo->appeal_requesting_date != null && $caseInfo->appeal_requesting_date != '') {
            $appeal_requesting_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->appeal_requesting_date)));
        } else {
            $appeal_requesting_date = null;
        }

        if (empty($caseInfo->case_entry_date)) {
            $case_entry_date = date('Y-m-d'); // Set to current date
        } else {

            $case_entry_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->case_entry_date)));
        }

        if ($caseInfo->proposal_date_civil_revision != null && $caseInfo->proposal_date_civil_revision != '') {
            $proposal_date_civil_revision = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->proposal_date_civil_revision)));
        } else {
            $proposal_date_civil_revision = null;
        }
        if ($caseInfo->proposal_date_writ != null && $caseInfo->proposal_date_writ != '') {
            $proposal_date_writ = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->proposal_date_writ)));
        } else {
            $proposal_date_writ = null;
        }
        if ($caseInfo->result == 1) {
            $in_favour_govt = 1;
        } else {
            $in_favour_govt = 0;
        }

        $caseOriginId = GovCaseRegister::where('id', $caseInfo->case_number_origin)->first();

        try {
            $case->case_no = $caseInfo->case_no;
            $case->case_category_id = $caseInfo->case_category;
            $case->case_type_id = $caseInfo->case_category_type;
            // $case->action_user_id = userInfo()->id;
            // $case->action_user_role_id = userInfo()->role_id;
            // $case->create_by = userInfo()->id;
            $case->year = $caseInfo->case_year;
            $case->appeal_office_id = $caseInfo->appeal_office;
            $case->concern_new_appeal_person_designation = $caseInfo->concern_new_appeal_person_designation;
            $case->concern_user_id = $caseInfo->concern_user_id;
            $case->postpond_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->postpond_date))) ?? '';
            $case->postponed_details = $caseInfo->postponed_details ?? '';
            $case->case_category_origin = $caseInfo->case_category_origin;
            $case->case_number_origin = $caseInfo->case_number_origin;
            $case->case_origin_id = $caseOriginId;
            // $case->is_appeal = 1;
            $case->is_final_order = $caseInfo->is_final_order ?? null;
            $case->result = $caseInfo->result ?? null;
            $case->in_favour_govt = $in_favour_govt;
            $case->result_short_details = $caseInfo->result_short_details ?? null;
            // $case->is_appeal = $caseInfo->is_appeal;
            $case->result_date = $result_date;
            $case->case_entry_date = $case_entry_date;
            $case->result_copy_asking_date = $result_copy_asking_date;
            $case->result_copy_receiving_date = $result_copy_receiving_date;
            $case->appeal_requesting_memorial = $caseInfo->appeal_requesting_memorial ?? null;
            $case->appeal_requesting_date = $appeal_requesting_date;
            $case->reason_of_not_appealing = $caseInfo->reason_of_not_appealing ?? null;
            $case->appeal_adalat = $caseInfo->appeal_adalat ?? null;
            $case->case_division_id = 1;

            if ($case->save()) {
                $caseId = $case->id;
                if ($caseInfo->case_number_origin != null && $caseInfo->case_number_origin != '') {
                    self::prevCaseStatusUpdate($caseInfo->case_number_origin);
                }
            }
        } catch (\Exception $e) {
            dd($e);
            $caseId = null;
        }
        return $caseId;
    }

    public static function prevCaseStatusUpdate($prevCaseNumber)
    {
        $data = [
            'is_appeal' => 1,
        ];
        $updatedVal = DB::table('gov_case_registers')
            ->where('gov_case_registers.id', $prevCaseNumber)
            ->update($data);
        return $updatedVal;
    }

    public static function storeAppealOrderTaken($caseInfo)
    {
        // $case = self::creatingObjectModel($caseInfo['case_id']);
        $goveCaseId = ($caseInfo['case_id']);
        $case = new AppealOrderTaken();

        // if ($caseInfo->appeal_submission_requesting_date != null && $caseInfo->appeal_submission_requesting_date != '') {
        //     $appeal_against_postpond_interim_order_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->appeal_against_postpond_interim_order_date)));
        // } else {
        //     $appeal_against_postpond_interim_order_date = null;
        // }
        if ($caseInfo->appeal_submission_requesting_date != null && $caseInfo->appeal_submission_requesting_date != '') {
            $appeal_submission_requesting_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->appeal_submission_requesting_date)));
        } else {
            $appeal_submission_requesting_date = null;
        }
        if ($caseInfo->tamil_requesting_date != null && $caseInfo->tamil_requesting_date != '') {
            $tamil_requesting_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->tamil_requesting_date)));
        } else {
            $tamil_requesting_date = null;
        }

        // if ($case->postponed_interim_have == 0) {
        //     $case->postponed_interim_have = $caseInfo->postponed_interim_have;
        // }

        // if ($case->postponed_interim_data_details == null) {
        //     $case->postponed_interim_data_details = $caseInfo->postponed_interim_data_details;
        // }

        try {
            $case->order_tamil_decision_taken = $caseInfo->order_tamil_decision_taken;
            $case->order_tamil_decision_data_details = $caseInfo->order_tamil_decision_data_details;
            $case->appeal_against_adesh_decision_taken = $caseInfo->appeal_against_adesh_decision_taken;
            $case->adesh_tamil_decision_yes_taken = $caseInfo->adesh_tamil_decision_yes_taken;
            $case->sending_request_for_appeal_against_intreim_person_solicitor = $caseInfo->sending_request_for_appeal_against_intreim_person_solicitor;
            $case->sending_request_for_appeal_against_intreim_person_law_officer = $caseInfo->sending_request_for_appeal_against_intreim_person_law_officer;
            $case->appeal_submission_requesting_date = $appeal_submission_requesting_date;
            $case->appeal_submission_requesting_memorial = $caseInfo->appeal_submission_requesting_memorial;
            // $case->appeal_against_postpond_interim_order_date = $appeal_against_postpond_interim_order_date;
            $case->soltrack_tracking_number_for_appeal_against_intreim_order = $caseInfo->soltrack_tracking_number_for_appeal_against_intreim_order;
            // $case->postponed_order = $caseInfo->postponed_order;
            // $case->appeal_against_postpond_interim_order = $caseInfo->appeal_against_postpond_interim_order;
            // $case->postponed_details = $caseInfo->postponed_details;
            // $case->appeal_against_postpond_interim_order_details = $caseInfo->appeal_against_postpond_interim_order_details;
            // $case->tamil_requesting_memorial = $caseInfo->tamil_requesting_memorial;
            // $case->tamil_requesting_date = $tamil_requesting_date;
            // $case->interim_order = $caseInfo->interim_order;
            // $case->interim_order_details = $caseInfo->interim_order_details;
            $case->gov_case_id = $goveCaseId;
            if ($case->save()) {
                $caseId = $case->id;
            }
        } catch (\Exception $e) {
            dd($e);
            $caseId = null;
        }
        return $caseId;
    }

    public static function checkAppealGovCaseExist($caseId)
    {
        if ($caseId != null) {
            $case = AppealGovCaseRegister::find($caseId);
        } else {
            $case = new AppealGovCaseRegister();
        }
        return $case;
    }

    public static function updateGovCaseAsFoward($request)
    {
        if ($request->main_min_id) {
            $case_data = [
                'case_status_id' => $request->status_id,
                'action_user_role_id' => $request->group,
                'selected_main_min_id' => $request->main_min_id,
            ];
        } elseif ($request->main_dept_id) {
            $case_data = [
                'case_status_id' => $request->status_id,
                'action_user_role_id' => $request->group,
                'selected_main_dept_id' => $request->main_dept_id,
            ];
        } elseif ($request->group == 36) {
            $case_data = [
                'case_status_id' => $request->status_id,
                'action_user_role_id' => $request->group,
                'ag_office_sending_date' => date("Y-m-d"),
            ];
        } else {
            $case_data = [
                'case_status_id' => $request->status_id,
                'action_user_role_id' => $request->group,
            ];
        }
        GovCaseRegister::whereId($request->case_id)->update($case_data);
    }
    public static function caseStatusByRoleId($roleID)
    {
        $roleID = userInfo()->role_id;
        $office = userInfo()->office_id;
        $query = GovCaseRegister::select('case_status_id', DB::raw('COUNT(id) as total_case'))->where('status', '!=', 3)->where('action_user_role_id', $roleID);
        if ($roleID == 29 || $roleID == 31) {
            $query->where('selected_main_min_id', $office);
        } elseif ($roleID == 32 || $roleID == 33) {
            $query->where('selected_main_dept_id', $office);
        }

        $case_status = $query->groupBy('case_status_id')->get();

        // 'ministry_id',
        // 'department_id',
        // dd($case_status);
        return $case_status;
    }
    public static function againestGovCases()
    {
        $roleID = userInfo()->role_id;
        $office = userInfo()->office_id;
        $query = GovCaseRegister::where('in_favour_govt', 0)->whereNull('result_copy_asking_date');
        if ($roleID != 27 && $roleID != 28) {
            $query->orWhereHas('bibadis', function ($q) use ($office) {
                $q->where('respondent_id', $office);
            });
        }

        $case_status = $query->count();
        return $case_status;
    }
    public static function sendToSolicotorCases()
    {
        $roleID = userInfo()->role_id;
        $office = userInfo()->office_id;
        $query = GovCaseRegister::whereNull('result_sending_date');
        if ($roleID != 27 && $roleID != 28) {
            $query->orWhereHas('bibadis', function ($q) use ($office) {
                $q->where('respondent_id', $office);
            });
        }

        $case_status = $query->count();
        return $case_status;
    }
    public static function sendToAgFromSolCases()
    {
        $roleID = userInfo()->role_id;
        $office = userInfo()->office_id;
        $query = GovCaseRegister::whereNull('result_sending_date_solisitor_to_ag');
        if ($roleID != 27 && $roleID != 28) {
            $query->orWhereHas('bibadis', function ($q) use ($office) {
                $q->where('respondent_id', $office);
            });
        }

        $case_status = $query->count();
        return $case_status;
    }
    public static function stepNotTakenAgainstPostpondOrderCases()
    {
        $roleID = userInfo()->role_id;
        $office = userInfo()->office_id;
        $query = GovCaseRegister::whereNull('appeal_against_postpond_interim_order');
        if ($roleID != 27 && $roleID != 28) {
            $query->orWhereHas('bibadis', function ($q) use ($office) {
                $q->where('respondent_id', $office);
            });
        }

        $case_status = $query->count();
        return $case_status;
    }
    public static function caseStatusByRoleIds($roleID = [])
    {
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

    public static function storeSendingReply($caseInfo)
    {
        // dd($caseInfo['case_id']);
        $case = self::checkGovCaseExist($caseInfo['case_id']);
        if ($caseInfo->result_sending_date != null && $caseInfo->result_sending_date != '') {
            $result_sending_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->result_sending_date)));
        } else {
            $result_sending_date = null;
        }
        if ($caseInfo->result_sending_date_solisitor_to_ag != null && $caseInfo->result_sending_date_solisitor_to_ag != '') {
            $result_sending_date_solisitor_to_ag = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->result_sending_date_solisitor_to_ag)));
        } else {
            $result_sending_date_solisitor_to_ag = null;
        }
        if ($caseInfo->reply_submission_date != null && $caseInfo->reply_submission_date != '') {
            $reply_submission_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->reply_submission_date)));
        } else {
            $reply_submission_date = null;
        }
        if ($caseInfo->tamil_requesting_date != null && $caseInfo->tamil_requesting_date != '') {
            $tamil_requesting_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->tamil_requesting_date)));
        } else {
            $tamil_requesting_date = null;
        }

        try {
            $case->result_sending_date = $result_sending_date;
            $case->result_sending_date_solisitor_to_ag = $result_sending_date_solisitor_to_ag;
            $case->reply_submission_date = $reply_submission_date;
            $case->result_sending_memorial = $caseInfo->result_sending_memorial;
            $case->result_sending_memorial_solisitor_to_ag = $caseInfo->result_sending_memorial_solisitor_to_ag;
            $case->tamil_requesting_memorial = $caseInfo->tamil_requesting_memorial;
            $case->tamil_requesting_date = $tamil_requesting_date;
            if ($case->save()) {
                $caseId = $case->id;
            }
        } catch (\Exception $e) {
            dd($e);
            $caseId = null;
        }
        return $caseId;
    }

    public static function storeSuspensionOrder($caseInfo)
    {
        // dd($caseInfo['case_id']);
        $case = self::checkGovCaseExist($caseInfo['case_id']);

        if ($caseInfo->appeal_against_postpond_interim_order_date != null && $caseInfo->appeal_against_postpond_interim_order_date != '') {
            $appeal_against_postpond_interim_order_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->appeal_against_postpond_interim_order_date)));
        } else {
            $appeal_against_postpond_interim_order_date = null;
        }
        if ($caseInfo->tamil_requesting_date != null && $caseInfo->tamil_requesting_date != '') {
            $tamil_requesting_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->tamil_requesting_date)));
        } else {
            $tamil_requesting_date = null;
        }

        try {
            $case->appeal_against_postpond_interim_order_date = $appeal_against_postpond_interim_order_date;
            $case->postponed_order = $caseInfo->postponed_order;
            $case->appeal_against_postpond_interim_order = $caseInfo->appeal_against_postpond_interim_order;
            $case->postponed_details = $caseInfo->postponed_details;
            $case->appeal_against_postpond_interim_order_details = $caseInfo->appeal_against_postpond_interim_order_details;
            $case->tamil_requesting_memorial = $caseInfo->tamil_requesting_memorial;
            $case->tamil_requesting_date = $tamil_requesting_date;
            $case->interim_order = $caseInfo->interim_order;
            $case->interim_order_details = $caseInfo->interim_order_details;
            if ($case->save()) {
                $caseId = $case->id;
            }
        } catch (\Exception $e) {
            dd($e);
            $caseId = null;
        }
        return $caseId;
    }

    public static function storeContemptCase($caseInfo)
    {
        // dd($caseInfo['case_id']);
        $case = self::checkGovCaseExist($caseInfo['case_id']);

        if ($caseInfo->contempt_case_isuue_date != null && $caseInfo->contempt_case_isuue_date != '') {
            $contempt_case_isuue_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->contempt_case_isuue_date)));
        } else {
            $contempt_case_isuue_date = null;
        }
        if ($caseInfo->contempt_case_answer_sending_date != null && $caseInfo->contempt_case_answer_sending_date != '') {
            $contempt_case_answer_sending_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->contempt_case_answer_sending_date)));
        } else {
            $contempt_case_answer_sending_date = null;
        }

        try {
            $case->contempt_case_no = $caseInfo->contempt_case_no;
            $case->others_action_detials = $caseInfo->others_action_detials;
            $case->contempt_case_isuue_date = $contempt_case_isuue_date;
            $case->contempt_case_answer_sending_date = $contempt_case_answer_sending_date;

            if ($case->save()) {
                $caseId = $case->id;
            }
        } catch (\Exception $e) {
            dd($e);
            $caseId = null;
        }
        return $caseId;
    }

    public static function storeLeaveToAppealInfo($caseInfo)
    {
        // dd($caseInfo['case_id']);
        $case = self::checkGovCaseExist($caseInfo['case_id']);

        if ($caseInfo->leave_to_appeal_date != null && $caseInfo->leave_to_appeal_date != '') {
            $leave_to_appeal_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->leave_to_appeal_date)));
        } else {
            $leave_to_appeal_date = null;
        }

        if ($caseInfo->proposal_date_leave_to_appeal != null && $caseInfo->proposal_date_leave_to_appeal != '') {
            $proposal_date_leave_to_appeal = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->proposal_date_leave_to_appeal)));
        } else {
            $proposal_date_leave_to_appeal = null;
        }

        try {
            $case->leave_to_appeal_no = $caseInfo->leave_to_appeal_no;
            $case->leave_to_appeal_date = $leave_to_appeal_date;
            $case->contents_of_proposal_leave_to_appeal = $caseInfo->contents_of_proposal_leave_to_appeal;
            $case->sending_motions_in_view_of_that_litigation_leave_to_appeal = $caseInfo->sending_motions_in_view_of_that_litigation_leave_to_appeal;
            $case->proposal_date_leave_to_appeal = $proposal_date_leave_to_appeal;
            $case->proposal_memorial_leave_to_appeal = $caseInfo->proposal_memorial_leave_to_appeal;
            $case->contact_email_leave_to_appeal = $caseInfo->contact_email_leave_to_appeal;
            $case->focal_person_name_leave_to_appeal = $caseInfo->focal_person_name_leave_to_appeal;
            $case->focal_person_designation_leave_to_appeal = $caseInfo->focal_person_designation_leave_to_appeal;
            $case->focal_person_mobile_leave_to_appeal = $caseInfo->focal_person_mobile_leave_to_appeal;

            if ($case->save()) {
                $caseId = $case->id;
            }
        } catch (\Exception $e) {
            dd($e);
            $caseId = null;
        }
        return $caseId;
    }

    public static function storeLeaveToAppealAnswerInfo($caseInfo)
    {
        $case = self::checkGovCaseExist($caseInfo['case_id']);

        if ($caseInfo->leave_to_appeal_order_date != null && $caseInfo->leave_to_appeal_order_date != '') {
            $leave_to_appeal_order_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->leave_to_appeal_order_date)));
        } else {
            $leave_to_appeal_order_date = null;
        }

        try {
            $case->leave_to_appeal_order_date = $leave_to_appeal_order_date;
            $case->leave_to_appeal_is_favour_of_gov = $caseInfo->leave_to_appeal_is_favour_of_gov;
            $case->leave_to_appeal_order_details = $caseInfo->leave_to_appeal_order_details;

            if ($case->save()) {
                $caseId = $case->id;
            }
        } catch (\Exception $e) {
            dd($e);
            $caseId = null;
        }
        return $caseId;
    }

    public static function checkConcernPersonExist($badiId)
    {
        if (isset($badiId)) {
            $badi = AppealGovCaseConcernPerson::find($badiId);
        } else {
            $badi = new AppealGovCaseConcernPerson();
        }
        return $badi;
    }



       ////// Appeal Data Migration /////////////

       public static function convertBanglaToEnglish($number)
       {
           if (is_null($number)) {
               return null;
           }

           $banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
           $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

           return str_replace($banglaDigits, $englishDigits, $number);
       }

       public static function storeDataMigrationGovCase($caseInfo)
       {
           try {
               $case = new AppealGovCaseRegister;

               if (!empty($caseInfo['case_no'])) {
                   $convertedCaseNo = self::convertBanglaToEnglish($caseInfo['case_no']);
                   $case->case_no = strtok($convertedCaseNo, '/');
               } else {
                   $case->case_no = null;
               }

               $case->case_division_id = $caseInfo['court'] ?? null;
               $case->case_category_id = $caseInfo['case_category'] ?? null;
               $case->case_type_id = $caseInfo['case_category_type'] ?? null;
               $case->year = $caseInfo['case_year'] ?? null;

               if (!empty($caseInfo['casedate'])) {
                   $case->case_entry_date = Date::excelToDateTimeObject($caseInfo['casedate'])->format('Y-m-d');
               } else {
                   $case->case_entry_date = null;
               }
               $case->created_by_office = $caseInfo['main_respondent'] ?? null;
               $case->appeal_petitioner_name = $caseInfo['badi_name_0'] ?? null;
               $case->subject_matter = $caseInfo['subject_matter'] ?? null;


               if ($case->save()) {
                   return $case->id;
               }
           } catch (\Exception $e) {
               Log::error('Error inserting case data: ' . $e->getMessage());
               return null;
           }
       }

      public static function cleanAddress($address)
       {
           $cleanedAddress = str_replace('_x000D_', ' ', $address);
           return trim(preg_replace('/\s+/', ' ', $cleanedAddress));
       }

    //    public static function storeDataMigrationBadi($caseInfo, $govCaseId)
    //    {
    //        $cleanedAddress = self::cleanAddress($caseInfo['badi_address_0']);
    //        if (isset($caseInfo['badi_name_0']) && !empty($cleanedAddress)) {
    //            $badi = new GovCaseBadi();
    //            $badi->gov_case_id = $govCaseId;
    //            $badi->name = $caseInfo['badi_name_0'];
    //            $badi->address = $cleanedAddress;
    //            $badi->save();
    //        }
    //    }


    //    public static function storeDataMigrationMainBibadi($caseInfo, $govCaseId)
    //    {
    //        if (isset($caseInfo['main_respondent'])) {
    //            $officeID = $caseInfo['main_respondent'];
    //            $bibadi = new GovCaseBibadi();
    //            $bibadi->gov_case_id = $govCaseId;
    //            $bibadi->respondent_id = $officeID;
    //            $bibadi->is_main_bibadi = 1;
    //            $bibadi->other_respondent_manual_name = null;
    //            $bibadi->department_id = null;
    //            $bibadi->save();
    //        }
    //    }
}
