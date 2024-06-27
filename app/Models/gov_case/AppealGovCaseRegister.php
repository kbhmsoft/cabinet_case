<?php

namespace App\Models\gov_case;

use App\Models\gov_case\GovCaseBadi;
use App\Models\gov_case\GovCaseRegister;
use App\Models\gov_case\GovCaseOffice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppealGovCaseRegister extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;
    protected $table = 'appeal_gov_case_register';
    protected $fillable = [
        'id',
        'case_no',
        'case_category_id',
        'case_type_id',
        'year',
        'appeal_office_id',
        'appeal_petitioner_name',
        'concern_new_appeal_person_designation',
        'concern_user_id',
        'postpond_date',
        'postponed_details',
        'case_number_origin',
        'case_origin_id',
        'case_category_origin',
        'writ_petitioner_name',
        'subject_matter',
        'case_order_date',
        'case_order_details',
        'is_final_order',
        'result',
        'in_favour_govt',
        'result_short_details',
        'is_appeal',
        'result_date',
        'result_copy_asking_date',
        'result_copy_receiving_date',
        'appeal_requesting_memorial',
        'appeal_requesting_date',
        'reason_of_not_appealing',
        'most_important',
        'case_entry_date'
    ];

    // public function bibadis()
    // {
    //     return $this->hasMany(GovCaseBibadi::class, 'appeal_gov_case_id', 'id');
    // }

    public function highcourtCaseDetail()
    {
        return $this->hasOne(GovCaseRegister::class, 'id', 'case_origin_id');
    }
    public function office()
    {
        return $this->hasOne(GovCaseOffice::class, 'id', 'appeal_office_id');
    }

    public function badis(){
        return $this->hasOne(GovCaseBadi::class, 'gov_case_id', 'case_origin_id');
    }

    public function case_origin()
    {
        return $this->hasOne(GovCaseRegister::class, 'id', 'case_origin_id');
    }
    public function govOffice()
    {
        return $this->hasOne(GovCaseOffice::class, 'id', 'appeal_office_id');
    }
}
