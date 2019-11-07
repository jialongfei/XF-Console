<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_role';

    protected $fillable = ['user_id', 'role_id', 'update_user_id', 'created_at', 'updated_at'];

    public $timestamps = true;
}
