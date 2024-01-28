<?php

namespace App\Models\gov_case;

use App\Models\CaseStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoptorOffice extends Model
{
	use HasFactory;

	public $timestamps = true;

	protected $fillable = [
        'id',
        'doptor_office_id',
        'level',
        'parent',
        'parent_doptor_id','parent_layer_id',

        'parent_name',
        'office_name_bn',
        'office_name_en',
        'status',
	];


}
