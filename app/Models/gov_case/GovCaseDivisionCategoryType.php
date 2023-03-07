<?php

namespace App\Models\gov_case;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovCaseDivisionCategoryType extends Model
{
	use HasFactory;

	protected $table = 'gov_case_division_categories_types';
	public $timestamps = true;
	protected $fillable = [
    'id',
	'gov_case_category_id',
	'name_bn',
	'name_en',
    'status',
	];
}
