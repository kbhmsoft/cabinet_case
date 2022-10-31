<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
	use HasFactory;
	protected $fillable = [
		'id', 'gov_case_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'file_submission_date', 'file_type', 'file_category', 'file_name', 'file_path'

	];
}
