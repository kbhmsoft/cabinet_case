<?php

namespace App\Models\gov_case;

use App\Models\gov_case\GovCaseBibadi;
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
        'concern_new_appeal_person_designation',
        'concern_user_id',
        'postpond_date',
        'postponed_details',
        'case_number_origin',
        'case_category_origin',
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
    ];

    public function bibadis()
    {
        return $this->hasMany(GovCaseBibadi::class, 'appeal_gov_case_id', 'id');
    }

    public function highcourtCaseDetail()
    {
        return $this->hasOne(GovCaseRegister::class, 'case_no', 'case_number_origin');
    }
    public function office()
    {
        return $this->hasOne(GovCaseOffice::class, 'id', 'appeal_office_id');
    }

}
