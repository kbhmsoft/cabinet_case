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
use App\Models\gov_case\GovCaseConcernPerson;
use App\Models\gov_case\GovCaseHighcourtAdalat;
use App\Models\gov_case\AppealGovCaseConcernPerson;

class GovCaseBadiBibadiRepository
{
    public static function storeBadi($caseInfo, $govCaseId)
    {
        //  dd($caseInfo);
        if ($caseInfo->badi_name) {
            foreach ($caseInfo->badi_name as $key => $val) {
                if ($caseInfo->badi_name[$key] != null) {
                    $badi = self::checkBadiExist($caseInfo->badi_id[$key]);
                    $badi->gov_case_id = $govCaseId;
                    $badi->name = $caseInfo->badi_name[$key];
                    $badi->address = $caseInfo->badi_address[$key];
                    $badi->save();
                }
            }

        }
    }

    public static function checkBadiExist($badiId)
    {
        if (isset($badiId)) {
            $badi = GovCaseBadi::find($badiId);
        } else {
            $badi = new GovCaseBadi();
        }
        return $badi;
    }



    public static function storeMainBibadi($caseInfo, $govCaseId)
    {
        $officeID = userInfo()->office_id;

                $bibadi = new GovCaseBibadi();
                $bibadi->gov_case_id = $govCaseId;
                $bibadi->respondent_id = $officeID;
                $bibadi->is_main_bibadi = 1;
                $bibadi->save();
    }

    public static function storeBibadi($caseInfo, $govCaseId)
    {
        foreach ($caseInfo->other_respondent as $key => $val) {
            if ($caseInfo->other_respondent[$key] != null) {

                $bibadi = self::checkBibadiExist($caseInfo->bibadi_id[$key]);
                $bibadi->gov_case_id = $govCaseId;
                $bibadi->respondent_id = $caseInfo->other_respondent[$key];
                $bibadi->other_respondent_manual_name = $caseInfo->other_respondent_manual_name[$key];
                $bibadi->save();
            }
        }
    }

    public static function storeBibadiForChangingMainRespondent($caseInfo, $govCaseId)
    {
        foreach ($caseInfo->other_respondent as $key => $val) {
            if ($caseInfo->other_respondent[$key] != null) {
                $bibadi = GovCaseBibadi::where('gov_case_id',$govCaseId)->first();
                $bibadi->gov_case_id = $govCaseId;
                $bibadi->respondent_id = $caseInfo->other_respondent[$key];
                $bibadi->save();
            }
        }

        foreach ($caseInfo->main_respondent as $key => $val) {
            if ($caseInfo->main_respondent[$key] != null) {
                $bibadi = GovCaseBibadi::where('gov_case_id',$govCaseId)->where('is_main_bibadi',1)->first();
                $bibadi->gov_case_id = $govCaseId;
                $bibadi->respondent_id = $caseInfo->main_respondent[$key];
                $bibadi->is_main_bibadi = 1;
                $bibadi->save();
            }
        }
    }

    public static function checkBibadiExist($bibadiId)
    {
        if (isset($bibadiId)) {
            $bibadi = GovCaseBibadi::find($bibadiId);
        } else {
            $bibadi = new GovCaseBibadi();
        }
        // dd($bibadi);
        return $bibadi;
    }


    public static function checkBibadiExistForChangingMainRespondent($bibadiId)
    {
        if (isset($bibadiId)) {
            $bibadi = GovCaseBibadi::find($bibadiId);
        }
        //  else {
        //     $bibadi = new GovCaseBibadi();
        // }
        return $bibadi;
    }

    public static function getBadiByCaseId($caseId)
    {
        $badi = GovCaseBadi::where('gov_case_id', $caseId)->first();
        return $badi;
    }

    public static function getBibadiByCaseId($caseId)
    {
        $bibadi = GovCaseBibadi::where('gov_case_id', $caseId)->get();
        return $bibadi;
    }
    public static function getMainBibadiByCaseId($caseId)
    {
        $main_bibadi = GovCaseBibadi::where('gov_case_id', $caseId)->where('is_main_bibadi', 1)->get();
        return $main_bibadi;
    }
    public static function getOthersBibadiByCaseId($caseId)
    {
        $other_bibadi = GovCaseBibadi::where('gov_case_id', $caseId)->where('is_main_bibadi', null)->get();
        return $other_bibadi;
    }
    public static function getConcernPersonByCaseId($caseId)
    {
        $other_bibadi = GovCaseConcernPerson::where('gov_case_id', $caseId)->get();
        return $other_bibadi;
    }

    public static function getAppealConcernPersonByCaseId($caseId)
    {
        $other_bibadi = AppealGovCaseConcernPerson::where('gov_case_id', $caseId)->get();
        return $other_bibadi;
    }
    public static function getJusticeNameByCaseId($caseId)
    {
        $justices = GovCaseHighcourtAdalat::where('gov_case_id', $caseId)->get();
        return $justices;
    }
}