<?php

namespace App\Models\gov_case;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdministrativeTribrunalHighcourtAdalat extends Model
{
    use HasFactory;
    protected $table = 'administrative_tribrunal_highcourt_adalats';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'gov_case_id',
        'administrative_adalat',
    ];

}
