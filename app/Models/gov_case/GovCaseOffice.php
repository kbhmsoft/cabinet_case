<?php

namespace App\Models\gov_case;

use App\Models\CaseStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovCaseOffice extends Model
{
	use HasFactory;
    protected $table = 'gov_case_office';
	public $timestamps = true;

	protected $fillable = [
        'id',
        'doptor_office_id',
        'level',
        'parent',
        'office_name_bn',
        'office_name_en',
        'office_ministry_id',
        'custom_layer_id',
        'parent_office_id',
        'status',
	];
}
