<?php

namespace App\Models\gov_case;

use App\Models\Office;
use App\Models\gov_case\GovCaseOffice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppealAdministrativeTribrunalBibadi extends Model
{
	use HasFactory;
	public $timestamps = true;

	protected $fillable = [
        'id',
        'gov_case_id',
        'respondent_id',
        'is_main_bibadi',
        'other_respondent_manual_name',
	];

    public function ministry(){
        return $this->hasOne(GovCaseOffice::class, 'doptor_office_id', 'respondent_id');
    }
}