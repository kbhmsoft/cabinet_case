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
}
