<?php

namespace App\Models\gov_case;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovCaseOfficeType extends Model
{
	use HasFactory;

	protected $table = 'gov_case_office_type';
	public $timestamps = false;

	
	protected $fillable = [
	'id',
	'type_name',	
	'type_name_bn',
	];
	
}
