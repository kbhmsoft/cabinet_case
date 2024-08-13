<?php

namespace App\Models\gov_case;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppealAdministrativeTribrunalBadi extends Model
{
	use HasFactory;


	protected $table = 'appeal_administrative_tribrunal_badis';
	public $timestamps = true;

	protected $fillable = [
    'id',
	'gov_case_id',
	'name',
    'spouse_name',
    'address',
	];
}