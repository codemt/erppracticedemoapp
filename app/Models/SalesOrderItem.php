<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrderItem extends Model
{
    use softDeletes;

    protected $table = "sales_order_item";
    protected $fillable = ['id','product_id','supplier_id','sales_order_id','model_no','qty','unit_value','total_value','list_price','manu_clearance','discount_applied','tax_value','created_by','updated_by','is_mail'];

    protected $dates = ['deleted_at'];

	protected $softDelete = true;
	public function getUnitValueAttribute($value){
        return number_format($value,2,'.','');
    }
    public function getTotalValueAttribute($value){
        return number_format($value,2,'.','');
    }
    public function getListPriceAttribute($value){
        return number_format($value,2,'.','');
    }
    public function getTaxValueAttribute($value){
        return number_format($value,2,'.','');
    }
}
