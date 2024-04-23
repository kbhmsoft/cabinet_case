<?php

namespace App\Models\gov_case;

use App\Models\Office;
use App\Models\gov_case\GovCaseOffice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Role;


class GovCaseConcernPerson extends Model
{
	use HasFactory;
	// protected $table = 'mouja';
	public $timestamps = true;

	protected $fillable = [
        'id',
        'gov_case_id',
        'concern_person_designation',
        'concern_user_id',
	];

    public function user(){
        return $this->hasOne(User::class, 'id', 'concern_user_id');
    }
    public function role(){
        return $this->hasOne(Role::class,'id', 'concern_person_designation');
    }
}
