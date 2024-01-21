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

class GovCaseBadiBibadiRepository
{
    public static function storeBadi($caseInfo, $govCaseId)
    {
        if ($caseInfo->badi_name) {
            foreach ($caseInfo->badi_name as $key => $val) {
                if ($caseInfo->badi_name[$key] != null) {
                    // dd($caseInfo->badi_name);
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

    public static function storeBibadi($caseInfo, $govCaseId)
    {
        foreach ($caseInfo->other_respondent as $key => $val) {
            if ($caseInfo->other_respondent[$key] != null) {
                $bibadi = self::checkBibadiExist($caseInfo->bibadi_id[$key]);
                $bibadi->gov_case_id = $govCaseId;
                $bibadi->respondent_id = $caseInfo->other_respondent[$key];
                $bibadi->save();
            }
        }
        foreach ($caseInfo->main_respondent as $key => $val) {
            if ($caseInfo->main_respondent[$key] != null) {
                $bibadi = self::checkBibadiExist($caseInfo->bibadi_id[$key]);
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
        return $bibadi;
    }

    public static function getBadiByCaseId($caseId)
    {
        $badi = GovCaseBadi::where('gov_case_id', $caseId)->get();
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

}
