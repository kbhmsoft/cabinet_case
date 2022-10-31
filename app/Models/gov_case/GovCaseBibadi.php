<?php

namespace App\Models\gov_case;

use App\Models\Office;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovCaseBibadi extends Model
{
	use HasFactory;
	// protected $table = 'mouja';
	public $timestamps = true;

	protected $fillable = [
        'id',
        'gov_case_id',
        'ministry_id',
        'department_id',
        'is_main_bibadi',
	];

    public function ministry(){
        return $this->hasOne(Office::class, 'id', 'ministry_id');
    }
    public function department(){
        return $this->hasOne(Office::class, 'id', 'department_id');
    }
}
