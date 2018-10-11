<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingAddress extends Model
{
    use softDeletes;

    protected $table = "billing_address";
    protected $fillable = ['id','address','area','company_id','state_id','city_id','title'];

    protected $dates = ['deleted_at'];

	protected $softDelete = true;
}
