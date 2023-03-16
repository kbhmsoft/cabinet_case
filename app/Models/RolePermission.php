<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'role_id', 'permission_id', 'created_by', 'updated_by'];

 
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function role() {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function permission() {
        return $this->belongsTo(Permission::class, 'permission_id');
    }



}
