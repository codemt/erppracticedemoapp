<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddressMaster extends Model
{
    use softDeletes;
	
    protected $table = "address_masters";
    protected $fillable =['id','supplier_id','company_id','customer_id','distributor_id','title','area','address','country_id','state_id','city_id','pincode'];


	protected $dates = ['deleted_at'];

	protected $softDelete = true;
}
