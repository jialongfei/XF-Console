<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class RolePer extends Model
{
    protected $table = 'role_per';

    protected $fillable = ['role_id', 'permission_id', 'update_user_id', 'created_at', 'updated_at'];

    public $timestamps = true;
}
