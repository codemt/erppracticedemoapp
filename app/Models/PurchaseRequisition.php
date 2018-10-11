<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequisition extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'purchase_requisition';
    protected $fillable = ['id','company_id','supplier_id','distributor_id','currency_status','delivery_terms','purchase_approval_status','total_price','po_no','project_name','payment_terms','purchase_approval_date','dollar_total_price','company_invoice_to','company_shipping_add','supplier_billing_add','dispatch_through','other ref','remark','created_by','updated_by','is_mail','accountant_updated_by','owner_updated_by'];
    public $timestamps = true;

    public function getCreatedAtAttribute($created_at){
    	$date = date('d M Y',strtotime($created_at));
    	return $date;
    }

    public function getPurchaseApprovalDateAttribute($purchase_approval_date){
    	if($purchase_approval_date != null){
	    	$date = date('d M Y',strtotime($purchase_approval_date));
	    	return $date;
	    }
    }

    public function getTotalPriceAttribute($total_price){
        $number = number_format($total_price,2, '.', '');
        return $number;
    }

    public function getDollarTotalPriceAttribute($dollar_total_price){
        $number = number_format($dollar_total_price,2, '.', '');
        return $number;
    }
}
