<?php

namespace App\Models\gov_case;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\gov_case\GovCaseOfficeType;

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

    public function office_type(){
        return $this->hasOne(GovCaseOfficeType::class,'id', 'level');
    }
	
}
