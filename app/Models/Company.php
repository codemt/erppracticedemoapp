<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company_master';
    protected $fillable = [
        'id', 'name','address', 'spoc_name','spoc_email', 'spoc_phone','gst_no', 'pan_no','bank_name', 'account_no','ifsc_code', 'branch'
    ];
}
