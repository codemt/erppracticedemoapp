<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManageStock extends Model
{
    use SoftDeletes;
    protected $table = 'manage_stock'; 
    
    protected $fillable = ['id', 'product_id','name_description','model_no','total_qty', 'total_physical_qty','total_blocked_qty','company_id','supplier_id','weight','current_market_price','open_po_qty','open_so_qty','po_qty']; 
    
    protected $dates = ['deleted_at'];

    protected $softDelete = true;
}
