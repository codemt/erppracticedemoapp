<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\AdminResetPasswordNotification;
// use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use Notifiable;

     protected $table = 'admins';
     protected $guard = "admin";

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = [
        'id','email', 'password','name','designation_id','region','status','team_id','address','bloodgroup','alternate_no','pan_no','aadhar_no','image','company_contact_no','company_property','date_of_joining','date_of_birth'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function setnameAttribute($name)
    {
        $set_name = ucwords($name);
        return $this->attributes['name'] = $set_name;
    }
    public function getnameAttribute($name)
    {
        return ucwords($name);
    }
    public function designation_list()
    {
        return $this->hasOne('App\Models\Designation','id','designation_id');
    }

    public function user_permissions(){
        return $this->hasMany('App\Models\UserPermission','user_id','id');
    }
    public function setdateOfJoiningAttribute($value) {
        $this->attributes['date_of_joining'] = date('Y-m-d', strtotime($value));
    }

    public function getdateOfJoiningAttribute($value) {
        return date('d-m-Y', strtotime($value));
    }

    public function setdateOfBirthAttribute($value) {
        $this->attributes['date_of_birth'] = date('Y-m-d', strtotime($value));
    }

    public function getdateOfBirthAttribute($value) {
        return date('d-m-Y', strtotime($value));
    }
}
