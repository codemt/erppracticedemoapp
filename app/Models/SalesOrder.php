<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SalesOrderItem;

class SalesOrder extends Model
{
    use SoftDeletes;
   // protected $hidden = ['image'];
    protected $table = 'sales_order';
    protected $fillable = [
        'id', 'so_no','po_no','order_date','billing_address','billing_title','shipping_address','areaname','customer_contact_name','customer_contact_email','customer_contact_no','contact_name','contact_email','contact_no','sales_person_id','company_id','stateid','cityid','payment_terms','pin_code','delivery','advanced_received','part_shipment','trasport','pkg_fwd','other_expense','reason_for_other_expense','fright','remarks','image','total_amount','grand_total','tax_subtotal','total_tax_amount','billing_id','status','countryid','customer_id','created_by','updated_by','project_name','location','total_qty','check_billing'
    ];
    protected $dates = ['deleted_at','created_at','updated_at'];

	protected $softDelete = true;

    public function setorderDateAttribute($value) {
		$this->attributes['order_date'] = date('Y-m-d', strtotime($value));
	}

	public function getorderDateAttribute($value) {
		return date('d-m-Y', strtotime($value));
	}

	public function getCreatedAtAttribute($created_at){
    	$date = date('d-m-Y',strtotime($created_at));
    	return $date;
    }
    public function gettrasportAttribute($value)
    {
        $set_value = ucwords($value);
        return $this->attributes['trasport'] = $set_value;
    }
    public function salesorderitem()
    {
        return $this->hasMany('App\Models\SalesOrderItem','sales_order_id','id')->leftjoin('supplier_masters','supplier_masters.id','=','sales_order_item.supplier_id')->leftjoin('product_master','product_master.id','=','sales_order_item.product_id');
    }
    public function getTotalAmountAttribute($value){
        return number_format($value,2,'.','');
    }
    public function getAdvancedReceivedAttribute($value){
        return number_format($value,2,'.','');
    }
    public function getFrightAttribute($value){
        return number_format($value,2,'.','');
    }
    public function getOtherExpenseAttribute($value){
        if($value != null){
            return number_format($value,2,'.','');
        }
    }
    public function getPkgFwdAttribute($value){
        return number_format($value,2,'.','');
    }
}
