<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewManageStock extends Model
{
    //
    use SoftDeletes;

    protected $table = 'new_manage_stocks'; 
    protected $fillable = ['id', 'product_id','name_description','model_no','total_qty', 'total_physical_qty','total_blocked_qty','company_id','supplier_id','weight','current_market_price','open_po_qty','open_so_qty','po_qty']; 
    
    protected $dates = ['deleted_at'];

    protected $softDelete = true;
}

