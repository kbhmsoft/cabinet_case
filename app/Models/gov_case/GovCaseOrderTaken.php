<?php

namespace App\Models\gov_case;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovCaseOrderTaken extends Model
{
    use HasFactory;
    protected $table = 'gov_case_order_takens';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'gov_case_id',
        'order_tamil_decision_taken',
        'order_tamil_decision_data_details',
        'appeal_against_adesh_decision_taken',
        'adesh_tamil_decision_yes_taken',
        'sending_request_for_appeal_against_intreim_person_solicitor',
        'sending_request_for_appeal_against_intreim_person_law_officer',
        'appeal_submission_requesting_date',
        'appeal_submission_requesting_memorial',
        'soltrack_tracking_number_for_appeal_against_intreim_order',
        'created_at',
        'updated_at',
    ];

    public function gov_case_register(){
        return $this->belongsTo(GovCaseRegister::class, 'id', 'gov_case_id');
    }
}
