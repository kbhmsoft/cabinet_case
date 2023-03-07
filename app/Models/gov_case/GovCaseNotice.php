<?php

namespace App\Models\gov_case;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Role;

class GovCaseNotice extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'description',	
	    'publish_date',	
	    'expiry_date',	
	    'notice_for',
	    'created_at',
	    'updated_at',
	    'created_by',

    ];
    public function user(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function role(){
        return $this->hasOne(Role::class,'id', 'notice_for');
    }
}
