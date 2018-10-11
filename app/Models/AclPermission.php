<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AclPermission extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'acl_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['route_name','method_name','module','route_method','action_name','main_module','sub_module','description','is_active'];

    //Get The all permission
    public static function getPermission(){

        $user_prems_temp = self::select('*')->where('is_active','1')->get()->toArray();
        // dd($user_prems_temp);
        $user_prems = [];

        foreach ($user_prems_temp as $key => $value) {
            $user_prems[$value['main_module']."_".$value['sub_module']][$value['id']] = $value['description'];
        }
        return $user_prems;
    }
    
}
