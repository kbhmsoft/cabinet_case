<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppealAttachment extends Model
{
	use HasFactory;

    
	protected $fillable = [
		'id', 'appeal_gov_case_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'file_submission_date', 'file_type', 'file_category', 'file_name', 'file_path'
	];
}