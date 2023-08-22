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
use Illuminate\Database\Eloquent\SoftDeletes;

class GovCaseRegister extends Model
{
	use HasFactory,SoftDeletes;

	public $timestamps = true;
    protected $fillable = [
		'id',
        'case_no',
        'case_type',
        'appeal_case_id',
        'date_issuing_rule_nishi',
        'action_user_id',
        'action_user_role_id',
        'court_id',
        'arji_file',
        'result',
        'result_file',
        'result_date',
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
        'case_type_id',
        'year',
        'concern_user_id',
        'subject_matter',
        'postponed_order',
        'postponed_details',
        'important_cause',
        'interim_order',
        'interim_order_details',
        'is_appeal',
        'result_sending_date',
        'result_sending_memorial',
        'result_sending_date_solisitor_to_ag',
        'result_sending_memorial_solisitor_to_ag',
        'appeal_against_postpond_interim_order',
        'appeal_against_postpond_interim_order_date',
        'appeal_against_postpond_interim_order_details',
        'reply_submission_date',
        'ag_office_sending_date',
        'result_short_dtails',
        'result_copy_asking_date',
        'result_copy_reciving_date',
        'selected_main_min_id',
        'selected_main_dept_id',
        'appeal_requesting_memorial',
        'appeal_requesting_date',
        'tamil_requesting_memorial',
        'tamil_requesting_date',
        'reason_of_not_appealing',
        'contempt_case_no',
        'contempt_case_isuue_date',
        'contempt_case_answer_sending_date',
        'contempt_case_order',
        'leave_to_appeal_no',
        'leave_to_appeal_date',
        'leave_to_appeal_order_date',
        'leave_to_appeal_order_details',
        'review_case_no',
        'review_case_date',
        'review_case_order_date',
        'review_case_order_details',
        'civil_appeal_order_date',
        'civil_appeal_order_details',
        'others_action_detials',
        'contents_of_proposal_civil_revision',
        'sending_motions_in_view_of_that_litigation_civil_revision',
        'proposal_date_civil_revision',
        'proposal_memorial_civil_revision',
        'contact_email_civil_revision',
        'focal_person_name_civil_revision',
        'focal_person_designation_civil_revision',
        'focal_person_mobile_civil_revision',
        'contents_of_proposal_civil_suit',
        'case_type_civil_suit',
        'case_number_civil_suit',
        'proposal_date_civil_suit',
        'proposal_memorial_civil_suit',
        'contact_email_civil_suit',
        'focal_person_name_civil_suit',
        'focal_person_designation_civil_suit',
        'focal_person_mobile_civil_suit',
        'contents_of_proposal_writ',
        'case_number_writ',
        'proposal_date_writ',
        'proposal_memorial_writ',
        'contact_email_writ',
        'focal_person_name_writ',
        'focal_person_designation_writ',
        'focal_person_mobile_writ',
        'contents_of_proposal_leave_to_appeal',
        'sending_motions_in_view_of_that_litigation_leave_to_appeal',
        'proposal_date_leave_to_appeal',
        'proposal_memorial_leave_to_appeal',
        'contact_email_leave_to_appeal',
        'focal_person_name_leave_to_appeal',
        'focal_person_designation_leave_to_appeal',
        'focal_person_mobile_leave_to_appeal',

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
    public function mainBibadis(){
        return $this->hasMany(GovCaseBibadi::class, 'gov_case_id', 'id')->where('is_main_bibadi',1);
    }
    public function hearings(){
        return $this->hasMany(GovCaseHearing::class, 'gov_case_id', 'id')->orderby('id', 'DESC');
    }
    public function div_category(){
        return $this->hasOne(GovCaseDivisionCategory::class, 'id', 'case_category_id');
    }

}
