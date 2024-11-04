<?php

namespace App\Models\gov_case;

use App\Models\gov_case\GovCaseBibadi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\gov_case\AdministrativeTribrunalBibadi;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdministrativeTribrunalAdalat extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'administrative_tribrunal_adalats';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'status',
    ];


   

}
