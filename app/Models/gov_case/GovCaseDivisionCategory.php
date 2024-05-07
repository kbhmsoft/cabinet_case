<?php

namespace App\Models\gov_case;

use App\Models\gov_case\GovCaseDivision;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovCaseDivisionCategory extends Model
{
    use HasFactory;

    // protected $table = 'mouja';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'gov_case_division_id',
        'name_bn',
        'name_en',
        'status',
    ];

    public function division()
    {
        return $this->hasOne(GovCaseDivision::class, 'id', 'gov_case_division_id');
    }

    public function govCase()
    {
        return $this->belongsTo(GovCaseRegister::class);
    }

}