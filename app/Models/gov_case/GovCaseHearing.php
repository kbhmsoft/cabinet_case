<?php

namespace App\Models\gov_case;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovCaseHearing extends Model
{
	use HasFactory;
	// protected $table = 'mouja';
	public $timestamps = true;
	protected $fillable = [
    'id',
	'gov_case_id',
	'hearing_date',
	'hearing_file',
    'comments',
    'hearing_result_file',
    'hearing_result_comments',
    'user_id',
    'created_at',
    'updated_at',
	];
}
