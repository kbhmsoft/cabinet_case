<?php

namespace App\Models\gov_case;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdministrativeTribrunalHighcourtAdalat extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'administrative_tribrunal_highcourt_adalats';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'status',
    ];

}