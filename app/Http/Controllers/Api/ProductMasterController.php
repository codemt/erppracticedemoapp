<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductMaster;
use App\Models\SupplierMaster;
use App\Models\CompanyMaster;
use Illuminate\Support\Facades\Auth;

class ProductMasterController extends Controller
{
    public $successStatus = 200;

    public function details()
    {
    	$columns = ['company_masters.company_name as company','supplier_masters.supplier_name as supplier','product_master.model_no','product_master.qty','product_master.created_at'];

    	$product_data = ProductMaster::select($columns)
    	->leftjoin('company_masters','company_masters.id','=','product_master.company_id')
    	->leftjoin('supplier_masters','supplier_masters.id','=','product_master.supplier_id')->get()->toArray();
    	//dd($product_data);
    	return response()->json(['success' => $product_data], $this->successStatus);
    }

    public function show($id)
    {
    	$product_data = ProductMaster::find($id);
    	//dd($product_data);
    	return response()->json(['success' => $product_data,'message'=>'Show all details successfully'], $this->successStatus);
    }
}
