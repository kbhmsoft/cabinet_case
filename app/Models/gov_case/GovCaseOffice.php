<?php

namespace App\Models\gov_case;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovCaseOffice extends Model
{
	use HasFactory;

	protected $table = 'gov_case_office';
	public $timestamps = false;

	
	protected $fillable = [
	'id',	
	'level',
	'parent_name',	
	'office_name_bn',	
	'office_name_en',	
	'status',	
	'type',
	'office_head_desig',
	];
	
}
