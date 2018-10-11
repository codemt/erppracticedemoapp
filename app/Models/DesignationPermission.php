<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignationPermission extends Model
{
    protected $table = 'designation_permissions';

    protected $fillable = ['designation_id','permission_id','created_by','updated_by'];

    public function getPermission(){
        return $this->hasOne('App\Models\AclPermission','id','permission_id');
    }
}
