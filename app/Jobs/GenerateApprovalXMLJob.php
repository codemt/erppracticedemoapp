<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Mail,Log;
use App\Models\SalesOrder;

class GenerateApprovalXMLJob{

	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public $salesorder_data;

	public function __construct($salesorder_data) {
		$this->salesorder_data = $salesorder_data;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle() {
		$salesorder_data = $this->salesorder_data;

		$sales_order_id = $salesorder_data['id'];
		
		$sales_data = SalesOrder::select('sales_order.*','countries.title as country_name','customer_masters.gst_no as customer_gst_no','company_masters.gst_no as company_gst_no','company_masters.company_name as company_name','states.title as state_name')->where('sales_order.id',$sales_order_id)
                            ->leftjoin('countries','countries.id','=','sales_order.countryid')
                            ->leftjoin('customer_masters','customer_masters.id','=','sales_order.customer_id')
                            ->leftjoin('company_masters','company_masters.id','=','sales_order.company_id')
                            ->leftjoin('states','states.id','=','sales_order.stateid')
                            ->with(array('salesorderitem'=>function($query){
                                    $query->select('sales_order_item.*','supplier_masters.supplier_name','product_master.qty as actual_qty');
                                }))
                            ->first()->toArray();
        
        $status_xml_view = view('admin.tally.so_order_xml',compact('sales_data'))->render();
        
        $file_name = $sales_data['so_no']."_so.xml";
       
        $current_date_dir = date('d-m-y');
        if($sales_data['company_id'] == config('Constant.Stellar')){
	        if(!is_dir(public_path()."/Tally/Stellar/SO/export/".$current_date_dir)){
	            mkdir(public_path()."/Tally/Stellar/SO/export/".$current_date_dir);
	        }

	        $path = public_path()."/Tally/Stellar/SO/export/".$current_date_dir."/".$file_name;
	        // chmod($path, 0755);
	        fopen($path,"w");

	        file_put_contents($path,$status_xml_view);
        }

        if($sales_data['company_id'] == config('Constant.Triton')){
	        if(!is_dir(public_path()."/Tally/Triton/SO/export/".$current_date_dir)){
	            mkdir(public_path()."/Tally/Triton/SO/export/".$current_date_dir);
	        }
	        
	        $path = public_path()."/Tally/Triton/SO/export/".$current_date_dir."/".$file_name;
	        // chmod($path, 0755);
	        fopen($path,"w");
	        
	        file_put_contents($path,$status_xml_view);
        }

	}
}
