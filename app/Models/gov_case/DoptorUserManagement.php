<?php

namespace App\Models\gov_case;

use App\Models\CaseStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoptorUserManagement extends Model
{
	use HasFactory;
    protected $table = 'doptor_user_managements';
	public $timestamps = true;

	protected $fillable = [
        'id',
        'office_type',
        'ministry',
        'div_office',
        'office_id',
        'user_role',
        'organogram_id',
        'status',
        'designation',
        'officeNameBn',
        'name_bng',
        'email'
	];


}
