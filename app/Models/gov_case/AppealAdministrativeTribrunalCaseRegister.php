<?php

namespace App\Models\gov_case;

use App\Models\gov_case\GovCaseBadi;
use App\Models\gov_case\GovCaseRegister;
use App\Models\gov_case\GovCaseOffice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppealAdministrativeTribrunalCaseRegister extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;
    protected $table = 'appeal_administrative_tribrunal_case_registers';
    protected $fillable = [
        'id',
        'case_no',
        'case_category_type',
        'case_year',
        'notice_given_date',
        'total_badi_number',
        'subject_matter',
        'money_amount',

    ];

    public function bibadis()
    {
        return $this->hasMany(AppealAdministrativeTribrunalBibadi::class, 'gov_case_id', 'id');
    }

    public function mainBibadis()
    {
        return $this->hasMany(AppealAdministrativeTribrunalBibadi::class, 'gov_case_id', 'id')->where('is_main_bibadi', 1);
    }

    public function concernPersons()
    {
        return $this->hasMany(ConcernPersonAppealAdministrativeTribrunal::class, 'gov_case_id', 'id')->where('concern_user_id', auth()->id());
    }

    public function badis()
    {
        return $this->hasMany(AppealAdministrativeTribrunalBadi::class, 'gov_case_id', 'id');
    }
}
