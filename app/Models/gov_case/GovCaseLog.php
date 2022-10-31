<?php

namespace App\Models\gov_case;

use App\Models\CaseStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovCaseLog extends Model
{
	use HasFactory;

	// protected $table = 'mouja';
	public $timestamps = true;

	protected $fillable = [
        'id',
        'gov_case_id',
        'case_status_id',
        'user_id',
        'sender_user_role_id',
        'receiver_user_role_id',
        'comments',
	];
    public function case_status(){
        return $this->hasOne(CaseStatus::class, 'id', 'case_status_id');
    }
    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function role(){
        return $this->hasOne(Role::class, 'id', 'sender_user_role_id');
    }
}
