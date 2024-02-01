<?php

namespace App\Models\gov_case;

use App\Models\CaseStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoptorOrganogram extends Model
{
	use HasFactory;

	public $timestamps = true;

	protected $fillable = [
        'id',
        'doptor_office_id',
        'sequence',
        'superiorUnit',
        'superiorDesignation',
        'level',
        'nameBn',
        'name',
        'shortNameBn',
        'originUnit',
        'shortName',
	];


}
