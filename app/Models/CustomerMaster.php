<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerMaster extends Model
{
    use softDeletes;
	
    protected $table = "customer_masters";
    protected $fillable =['id','name','area','person_name','person_email','person_phone','gst_no','pan_no','company_id'];


	protected $dates = ['deleted_at'];

	protected $softDelete = true;

	public function settitleAttribute($name)
    {
    	$set_name = ucfirst($name);
    	return $this->attributes['name'] = $set_name;
    }
}
