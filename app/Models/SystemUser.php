<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemUser extends Model
{
    protected $table="system_users";
    protected $fillable = ['id','name','role_id','status','email','pincode','mobile'];
}
