<?php

namespace App\Models\gov_case;

use App\Models\gov_case\GovCaseBadi;
use App\Models\gov_case\GovCaseRegister;
use App\Models\gov_case\GovCaseOffice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdministrativeTribrunalCaseRegister extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;
    protected $table = 'administrative_tribrunal_case_registers';
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

}
