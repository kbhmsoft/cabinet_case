<?php

namespace App\Models\gov_case;

use App\Models\CaseStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovCaseActivityLog extends Model
{
	use HasFactory;

	public $timestamps = true;

	protected $fillable = [
        'id',
        'gov_case_id',
        'user_id',
        'user_roll_id',
        'activity_type',
        'message',
        'office_id',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent',
        'created_at',
        'updated_at',
	];

    public function role() {
        return $this->hasOne(Role::class, 'id', 'user_roll_id');
    }
    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
