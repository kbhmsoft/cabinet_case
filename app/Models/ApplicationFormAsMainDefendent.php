<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\gov_case\GovCaseDivisionCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicationFormAsMainDefendent extends Model
{
    // protected $guarded = [];
    use HasFactory;
    protected $table = 'application_form_as_main_defendents';
    public $timestamps = true;
    protected $fillable = [
        'court',
        'case_no',
        'case_category',
        'case_category_type',
        'main_defendant_comments',
        'additional_comments',
        'main_defendant_pdf',
        'office_id'
    ];

    public function category(){

        return $this->hasOne(GovCaseDivisionCategory::class,'id', 'gov_case_category_id');
    }
    public function court()
    {
        return $this->belongsTo(Court::class);
    }
}
