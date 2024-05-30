<?php

namespace App\Models\gov_case;

use App\Models\CaseStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovCaseMessage extends Model
{
	use HasFactory;


	public $timestamps = true;

	protected $fillable = [
        'id',
        'office_type',
        'ministry',
        'div_office',
        'office_id',
        'messages',
        'role',
        'selected_office_ids',
        'selected_user_ids'
	];


    protected $casts = [
        'selected_office_ids' => 'array',
        'selected_user_ids' => 'array'
    ];

}