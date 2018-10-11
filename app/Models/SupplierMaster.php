<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SupplierMaster extends Model
{
	use softDeletes;
	
    protected $table = "supplier_masters";
    protected $fillable =['id','address','supplier_name','spoc_name','spoc_email','spoc_phone','gst_no','pan_no','bankname','ac_number','ifsc_code','branch','state_id','city_id','pincode','country_id','company_id'];


	protected $dates = ['deleted_at'];

	protected $softDelete = true;
}
