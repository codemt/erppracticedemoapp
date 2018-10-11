<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequisitionDetails extends Model
{
   	protected $table = 'purchase_requisition_detail';
    protected $fillable = ['id','purchase_requisition_id','model_no','qty','product_name','unit_price','total_price','dollar_price','last_po','last_po2','updated_by'];
    public $timestamps = true;

	public function getUnitPriceAttribute($unit_price){
		$number = number_format($unit_price,2, '.', '');
		return $number;
		dd($number);
	}

	public function getTotalPriceAttribute($total_price){
		$number = number_format($total_price,2);
		return $number;
	}

	public function getDollarPriceAttribute($dollar_price){
		$number = number_format($dollar_price,2);
		return $number;
	}
}
