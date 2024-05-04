<?php

namespace App\Models\gov_case;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class GovCaseAppealAdalat extends Model
{
	use HasFactory;
	public $timestamps = true;
    protected $table = 'gov_case_appeal_adalats';
	protected $fillable = [
        'id',
        'gov_case_id',
        'appeal_adalat',
	];

    // public function user(){
    //     return $this->hasOne(User::class, 'id', 'concern_user_id');
    // }
    // public function role(){
    //     return $this->hasOne(Role::class,'id', 'concern_person_designation');
    // }
}