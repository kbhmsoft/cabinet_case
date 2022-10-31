<?php

namespace App\Models\gov_case;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovCaseDivision extends Model
{
	use HasFactory;

	// protected $table = 'mouja';
	public $timestamps = true;

	protected $fillable = [
    'id',
	'name_bn',
	'name_en',
    'status',
	];
}
