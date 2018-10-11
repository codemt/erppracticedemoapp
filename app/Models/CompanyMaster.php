<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyMaster extends Model
{
    use softDeletes;
	
    protected $table = "company_masters";
    protected $fillable =['id','company_name','billing_address','spoc_name','spoc_email','spoc_phone','gst_no','pan_no','bankname','ac_number','ifsc_code','branch','state','city','billing_pincode','shipping_name','shipping_email','shipping_phone'];


	protected $dates = ['deleted_at'];

	protected $softDelete = true;

	public function settitleAttribute($company_name)
    {
    	$set_name = ucfirst($company_name);
    	return $this->attributes['company_name'] = $set_name;
    }
}
