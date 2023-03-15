<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'display_name', 'parent_permission_name_id', 'user_id', 'status'];

 
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parentName() {
        return $this->belongsTo(ParentPermissionName::class, 'parent_permission_name_id');
    }

  

}
