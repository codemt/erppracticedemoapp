<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distributor extends Model
{
   use softDeletes;
	
    protected $table = "distributors";
    protected $fillable =['id','distributor_name','spoc_name','spoc_email','spoc_phone','gst_no','pan_no','bankname','ac_number','ifsc_code','branch','company_id'];


	protected $dates = ['deleted_at'];

	protected $softDelete = true;
}
