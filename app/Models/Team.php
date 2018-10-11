<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = "user_team";
    protected $fillable = ['id','name'];
}
