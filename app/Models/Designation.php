<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
{
   use SoftDeletes;
    protected $table = 'designations';

    protected $dates = ['deleted_at'];
	protected $softDelete = true;

	protected $fillable = ['id', 'name','description','team_id','status'];


	public function getNameAttribute($value)
	{
		return $this->attribute = ucwords($value);
	}
	public function permissions()
    {
        return $this->belongsToMany('App\Models\AclPermission','user_permissions','permission_id','route_name');
    } 
}
