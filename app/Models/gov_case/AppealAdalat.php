<?php

namespace App\Models\gov_case;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppealAdalat extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'appeal_adalats';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'status',
    ];

}
