<?php

namespace App\Models\gov_case;

use App\Models\CaseStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinistryLayers extends Model
{
	use HasFactory;

	public $timestamps = true;

	protected $fillable = [
        'id',
        'min_id',
        'layer_id',
        'parent_layer_id',
        'layer_name_bng',
        'layer_name_eng',
        'status',
	];


}
