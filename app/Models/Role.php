<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
	use softDeletes;

    protected $table = "role";
    protected $fillable = ['id','name','description'];

    protected $dates = ['deleted_at'];

	protected $softDelete = true;
}
