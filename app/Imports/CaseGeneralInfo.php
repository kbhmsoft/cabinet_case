<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\gov_case\GovCaseRegister;
use App\Models\gov_case\GovCaseConcernPerson;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\gov_case\GovCaseHighcourtAdalat;

class CaseGeneralInfo implements ToModel, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
        $govCase = GovCaseRegister::create([
            'case_no' => $row['case_no'],
            'case_type' => $row['case_type'],
            'date_issuing_rule_nishi' => date('Y-m-d', strtotime(str_replace('/', '-', $row['case_date']))),
            'action_user_id' => userInfo()->id,
            'action_user_role_id' => userInfo()->role_id,
            'create_by' => userInfo()->id,
            'year' => $row['case_year'],
            'date_issuing_rule_nishi' => date('Y-m-d', strtotime(str_replace('/', '-', $row['case_date']))),
            'case_division_id' => $row['court'],
            'case_category_id' => $row['case_category'],
            'case_type_id' => $row['case_category_type'],
            'subject_matter' => $row['subject_matter'],
            'total_badi_number' => $row['total_badi_number'] ?? 0,
            'money_amount' => str_replace(',', '', $row['money_amount']),
            'postponed_interim_have' => $row['postponed_interim_have'],
            'postponed_interim_data_details' => $row['postponed_interim_data_details'],
        ]);
        GovCaseConcernPerson::create([
            'gov_case_id' => $govCase->id,
            'concern_person_designation' => $row['concernPersonDesignation'],
            'concern_user_id' => $row['concern_user_id'],
        ]);
        GovCaseHighcourtAdalat::create([
            'gov_case_id' => $govCase->id,
            'highcourt_adalat' => $row['highcourt_adalat'],
        ]);
        return $govCase;
    }
}
