<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentPermission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'status'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function permissions() {
        return $this->hasMany(Permission::class, 'parent_permission_id')->orderBy('created_at', 'ASC');
    }


}
