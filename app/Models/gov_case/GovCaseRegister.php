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

class GovCaseRegister extends Model
{
	use HasFactory;


	// protected $table = 'mouja';
	public $timestamps = true;

	protected $fillable = [
		'id',
		'case_no',
		'date_issuing_rule_nishi',
		'action_user_id',
		'action_user_role_id',
		'arji_file',
		'court_id',
		'result',
		'result_file',
		'status',
		'comments',
		'in_favour_govt',
		'create_by',
		'govt_lost_reason',
		'gov_case_ref_id',
		'ref_gov_case_no',
		'case_status_id',
		'case_division_id',
		'case_category_id',
		'year',
		'concern_user_id',
		'subject_matter',
		'postponed_details',
		'important_cause',
		'interim_order',
	];

    public function users(){
        return $this->hasOne(Users::class,'id', 'concern_user_id');
    }

    public function court(){
        return $this->hasOne(Court::class,'id', 'court_id');
    }
    public function role(){
        return $this->hasOne(Role::class,'id', 'action_user_role_id');
    }

    public function advocate(){
        return $this->hasOne(User::class,'id', 'concern_user_id');
    }

    public function case_status(){
        return $this->hasOne(CaseStatus::class, 'id', 'case_status_id');
    }

    public function case_division(){
        return $this->hasOne(GovCaseDivision::class, 'id', 'case_division_id');
    }

    public function case_category(){
        return $this->hasOne(GovCaseDivisionCategory::class, 'id', 'case_division_id');
    }
    public function badis(){
        return $this->hasMany(GovCaseBadi::class, 'gov_case_id', 'id');
    }
    public function bibadis(){
        return $this->hasMany(GovCaseBibadi::class, 'gov_case_id', 'id');
    }
    public function hearings(){
        return $this->hasMany(GovCaseHearing::class, 'gov_case_id', 'id')->orderby('id', 'DESC');
    }
    public function div_category(){
        return $this->hasOne(GovCaseDivisionCategory::class, 'id', 'case_category_id');
    }

}
