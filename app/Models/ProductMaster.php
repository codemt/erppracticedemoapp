<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMaster extends Model
{
	use softDeletes;

    protected $table = 'product_master';
    protected $fillable = ['company_id','supplier_id','product_type','combo_product','model_no','name_description','price','unit','max_discount','tax','qty','min_qty','image','hsn_code','weight','product_status','created_at','updated_at'];
    protected $dates = ['deleted_at'];

	protected $softDelete = true;

	public function setNameDescriptionAttribute($name_description){
		$this->attributes['name_description'] = ucfirst($name_description);
	}

	public function getPriceAttribute($price){
		$number = number_format($price,2, '.', '');
		return $number;
	}

	public function getMaxDiscountAttribute($max_discount){
		$number = number_format($max_discount,2, '.', '');
		return $number;
	}

	public function getUnitAttribute($unit){
		$number = number_format($unit,2, '.', '');
		return $number;
	}

	public function getWeightAttribute($weight){
		$number = number_format($weight,2, '.', '');
		return $number;
	}
}
