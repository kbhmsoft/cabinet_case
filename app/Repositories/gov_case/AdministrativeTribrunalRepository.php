<?php

namespace App\Repositories\gov_case;

use App\Models\Attachment;
use App\Models\FinalAttachment;
use App\Models\ReplyAttachment;
use Illuminate\Support\Facades\DB;
use App\Models\SuspensionAttachment;
use Illuminate\Support\Facades\Auth;
use App\Models\gov_case\GovCaseHearing;
use App\Models\gov_case\GovCaseRegister;
use App\Models\gov_case\GovCaseOrderTaken;
use App\Models\gov_case\GovCaseConcernPerson;
use App\Models\gov_case\GovCaseHighcourtAdalat;
use App\Models\AdministrativeTribrunalAttachment;
use App\Models\AppealAdministrativeTribrunalAttachment;
use App\Models\gov_case\AdministrativeTribrunalCaseRegister;
use App\Models\gov_case\ConcernPersonAdministrativeTribrunal;
use App\Models\gov_case\AdministrativeTribrunalHighcourtAdalat;
use App\Models\gov_case\AppealAdministrativeTribrunalCaseRegister;
use App\Models\gov_case\ConcernPersonAppealAdministrativeTribrunal;
use App\Models\gov_case\AppealAdministrativeTribrunalHighcourtAdalat;

class AdministrativeTribrunalRepository
{
    public static function AdministrativeTribrunalAllDetails($caseId)
    {
        $case = AdministrativeTribrunalCaseRegister::findOrFail($caseId);
        $caseBadi = GovCaseBadiBibadiRepository::getAdministrativeTribrunalBadiByCaseId($caseId);
        $caseLawers = GovCaseBadiBibadiRepository::getATConcernPersonByCaseId($caseId);
        $caseCourts = GovCaseBadiBibadiRepository::getATJusticeNameByCaseId($caseId);
        $caseBibadi = GovCaseBadiBibadiRepository::getATBibadiByCaseId($caseId);
        $mainBibadi = GovCaseBadiBibadiRepository::getATMainBibadiByCaseId($caseId);
        $otherBibadi = GovCaseBadiBibadiRepository::getATOthersBibadiByCaseId($caseId);
        $caseMainBibadi = GovCaseBadiBibadiRepository::getMainBibadiByCaseId($caseId);


        $adminisTrativeTribunalFiles = AdministrativeTribrunalAttachment::where('gov_case_id', $caseId)->get();

        $data = [
            'case' => $case,
            'caseBadi' => $caseBadi,
            'caseLawers' => $caseLawers,
            'caseCourts' => $caseCourts,
            'caseMainBibadi' => $caseMainBibadi,
            'caseBibadi' => $caseBibadi,
            'mainBibadi' => $mainBibadi,
            'otherBibadi' => $otherBibadi,
            'adminisTrativeTribunalFiles' => $adminisTrativeTribunalFiles,

        ];

        return $data;
    }


    public static function AppealAdministrativeTribrunalAllDetails($caseId)
    {
        $case = AppealAdministrativeTribrunalCaseRegister::findOrFail($caseId);
        $caseBadi = GovCaseBadiBibadiRepository::getAppealAdministrativeTribrunalBadiByCaseId($caseId);
        $caseLawers = GovCaseBadiBibadiRepository::getAATConcernPersonByCaseId($caseId);
        $caseCourts = GovCaseBadiBibadiRepository::getAATJusticeNameByCaseId($caseId);
        $caseBibadi = GovCaseBadiBibadiRepository::getAATBibadiByCaseId($caseId);
        $mainBibadi = GovCaseBadiBibadiRepository::getAATMainBibadiByCaseId($caseId);
        $otherBibadi = GovCaseBadiBibadiRepository::getAATOthersBibadiByCaseId($caseId);
        $caseMainBibadi = GovCaseBadiBibadiRepository::getMainBibadiByCaseId($caseId);


        $appealAdminisTrativeTribunalFiles = AppealAdministrativeTribrunalAttachment::where('gov_case_id', $caseId)->get();

        $data = [
            'case' => $case,
            'caseBadi' => $caseBadi,
            'caseLawers' => $caseLawers,
            'caseCourts' => $caseCourts,
            'caseMainBibadi' => $caseMainBibadi,
            'caseBibadi' => $caseBibadi,
            'mainBibadi' => $mainBibadi,
            'otherBibadi' => $otherBibadi,
            'appealAdminisTrativeTribunalFiles' => $appealAdminisTrativeTribunalFiles,

        ];

        return $data;
    }

    public static function storeAppealAdministrativeTribrunalGeneralInfo($caseInfo)
    {
        try {
            $case = self::checkAppealAdministrativeTribrunalCaseExist($caseInfo['case_id']);

            $petitioner_name = '';
            if ($caseInfo->appeal_office == 0) {
                $petitioner_name = $caseInfo->appeal_petitioner_name;
            }

            $case->case_no = $caseInfo->case_no;
            $case->case_category_type = 1;
            $case->notice_given_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->notice_given_date)));
            $case->create_by = userInfo()->id;
            $case->case_year = $caseInfo->case_year;
            $case->adalat_name = $caseInfo->adalat_name;
            $case->case_number_at_origin = $caseInfo->case_number_at_origin;
            $case->court = $caseInfo->court;
            $case->subject_matter = $caseInfo->subject_matter;
            $case->total_badi_number = $caseInfo->total_badi_number ?? 0;
            $case->appeal_petitioner_name = $petitioner_name;
            $case->appeal_office_id = $caseInfo->appeal_office;
            $case->created_by = Auth::user()->id;
            $case->created_by_office = Auth::user()->office_id;

            $case->at_case_number_origin_manual = $caseInfo->at_case_number_origin_manual;
            $case->writ_petitioner_name = $caseInfo->writ_petitioner_name;
            $case->at_case_subject_matter = $caseInfo->at_case_subject_matter;
            // $case->case_order_date = $caseInfo->case_order_date;
            if (empty($caseInfo->case_order_date)) {
                $case->case_order_date = date('Y-m-d');
            } else {
                $case->case_order_date = date('Y-m-d', strtotime(str_replace('/', '-', $caseInfo->case_order_date)));
            }
            $case->at_case_order_details = $caseInfo->at_case_order_details;



            // $case->money_amount = str_replace(',', '', $caseInfo->money_amount);

            if ($case->save()) {
                $caseId = $case->id;
            }
        } catch (\Exception $e) {
            dd($e);
            $caseId = null;
        }
        return $caseId;
    }

    public static function checkAppealAdministrativeTribrunalCaseExist($caseId)
    {
        if ($caseId != null) {
            $case = AppealAdministrativeTribrunalCaseRegister::find($caseId);
        } else {
            $case = new AppealAdministrativeTribrunalCaseRegister();
        }
        return $case;
    }

    public static function storeConcernPersonAppealAdministritiveTribrunal($caseInfo, $govCaseId)
    {
        if ($caseInfo->concernPersonDesignation) {
            foreach ($caseInfo->concernPersonDesignation as $key => $val) {
                if ($caseInfo->concernPersonDesignation[$key] != null) {

                    $concernPrerson = self::checkConcernPersonAppealAdministritiveTribrunalExist($caseInfo->concern_person_id[$key]);
                    $concernPrerson->gov_case_id = $govCaseId;
                    $concernPrerson->concern_person_designation = $caseInfo->concernPersonDesignation[$key];
                    $concernPrerson->concern_user_id = $caseInfo->concern_user_id[$key];

                    $concernPrerson->save();
                }
            }
        }
    }

    public static function checkConcernPersonAppealAdministritiveTribrunalExist($badiId)
    {
        if (isset($badiId)) {
            $badi = ConcernPersonAppealAdministrativeTribrunal::find($badiId);
        } else {
            $badi = new ConcernPersonAppealAdministrativeTribrunal();
        }
        return $badi;
    }



    
}
