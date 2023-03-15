<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	use HasFactory;

	protected $table = 'roles';
	// public $timestamps = true;   

	protected $fillable = [
	'role_name', 'status', 'user_id'
	];

	public function users() {
		return $this->HasMany(User::class);
	}

	public function user() {
		return $this->belongsTo(User::class, 'user_id');
	}

	// public function subcategories(){
	// 	return $this->hasMany('App\CaseRegister', 'upazila_id');
	// }
}
