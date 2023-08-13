<?php

namespace App\Models\gov_case;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Court;
use App\Models\Division;
use App\Models\gov_case\GovCaseDivision;
use App\Models\gov_case\GovCaseDivisionCategory;
use App\Models\CaseStatus;
use App\Models\Role;
use App\Models\Upazila;
use App\Models\Mouja;
use App\Models\User;

class AppealGovCaseRegister extends Model
{
	use HasFactory;

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
	];

}
