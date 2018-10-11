<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Auth,PDF,DB,Log;
use App\Models\AddressMaster;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;

class SalesOrderApprovalPDFJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $sales_order_data;
    public function __construct($sales_order_data) {
        $this->sales_order_data = $sales_order_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sales_order_data = $this->sales_order_data;
        // \Log::info($sales_order_data);
        $id = $sales_order_data['id'];

        $sales_person_name = $sales_order_data['user'];
        
        $sales_order_data = SalesOrder::select('sales_order.*','company_masters.company_name','address_masters.pincode as bill_pincode','countries.title as ship_country','states.title as ship_state','cities.title as ship_city','company_masters.pan_no as pan_no','company_masters.gst_no as gst_no','company_masters.state as company_state')->where('sales_order.id',$id)
                            ->leftjoin('company_masters','company_masters.id','sales_order.company_id')
                            ->leftjoin('address_masters','address_masters.id','sales_order.billing_id')
                            ->leftjoin('countries','countries.id','sales_order.countryid')
                            ->leftjoin('states','states.id','sales_order.stateid')
                            ->leftjoin('cities','cities.id','sales_order.cityid')
                            ->first();
        // dd($sales_order_data);                    
        $billing_data = AddressMaster::select('countries.title as bill_country','states.title as bill_state','cities.title as bill_city')->where('address_masters.id',$sales_order_data['billing_id'])->leftjoin('countries','countries.id','address_masters.country_id')->leftjoin('states','states.id','address_masters.state_id')->leftjoin('cities','cities.id','address_masters.city_id')->first();   

        $sales_order_data['bill_country'] = $billing_data['bill_country'];
        $sales_order_data['bill_state'] = $billing_data['bill_state'];
        $sales_order_data['bill_city'] = $billing_data['bill_city'];

        $billing_address = explode(',',$sales_order_data['billing_address']);

        $sales_order_item = SalesOrderItem::select('sales_order_item.*','supplier_masters.supplier_name','product_master.name_description as name_description','product_master.hsn_code as hsn_code')->where('sales_order_id',$id)
                            ->leftjoin('supplier_masters','supplier_masters.id','=','sales_order_item.supplier_id')
                            ->leftjoin('product_master','product_master.id','=','sales_order_item.product_id')
                            ->get()->toArray();
        
        $order_item = array();
        
        foreach ($sales_order_item as $key => $value) {
            $order_item[$value['supplier_name']][] = $value;
        }      
        //igst and cgst         
        $igst = false;
        $cgst_sgst = false;
        
        if($sales_order_data['company_state'] == $sales_order_data['stateid'])
        {
            $cgst_sgst = true;
        }else{
            $igst = true;
        }

        //round off
        $total_round_off_price = $sales_order_data['grand_total'];

        $floor_value = floor($total_round_off_price);
        $decimal_value = number_format($total_round_off_price - $floor_value,2,'.','');

        if($decimal_value == 0.00){
            $round_off_value = number_format(0.00,2,'.','');
            $total_price_tax = number_format($total_round_off_price,2,'.','');
        }
        else if($decimal_value > 0.00 && $decimal_value < 0.50){
            $round_off_value = number_format($decimal_value,2,'.','');
            $total_price_tax = number_format($floor_value,2,'.','');
        }
        else{
            $round_off_value = number_format(1.00 - $decimal_value,2,'.','');
            $total_price_tax = number_format($floor_value + 1,2,'.','');
        }

       

        //HSN
        $hsn_codes = SalesOrderItem::select(DB::raw('SUM(total_value) as total_hsn_value,product_master.hsn_code as hsn_code'))->where('sales_order_id',$id)
                ->leftjoin('supplier_masters','supplier_masters.id','=','sales_order_item.supplier_id')
                ->leftjoin('product_master','product_master.id','=','sales_order_item.product_id')
                ->groupBy('product_master.hsn_code')
                ->get()->toArray();
        $total_taxable_value = 0;        
        $igst_total = 0;
        $cgst_sgst_total = 0;
        foreach ($hsn_codes as $key => $value) {
            $total_taxable_value = number_format($total_taxable_value+$value['total_hsn_value'],2,'.','');
            $igst_total = $igst_total + (($value['total_hsn_value']*18)/100);
            $cgst_sgst_total = $cgst_sgst_total + (($value['total_hsn_value']*9)/100);
        }

        $total_taxable_value = $total_taxable_value + $sales_order_data['pkg_fwd'] + $sales_order_data['fright'];
        //rupees in word
        $total_in_word = explode('.',$sales_order_data['grand_total']);
        
        $sales_order_data['cgst_sgst'] = $cgst_sgst;
        $sales_order_data['igst'] = $igst;
        $sales_order_data['total_in_word'] = $total_in_word;
        $sales_order_data['round_off'] = $round_off_value;
        $sales_order_data['grandTotal'] = $total_price_tax;
        $sales_order_data['total_taxable_value'] = $total_taxable_value;
        $sales_order_data['igst_total'] = $igst_total;
        $sales_order_data['cgst_sgst_total'] = $cgst_sgst_total;


        $ntw = new \NTWIndia\NTWIndia();
        $mpdf = new \Mpdf\Mpdf(['tempDir' => public_path() . '/pdf/salesorder/tmp']);
        $header = "<img src='".public_path() .'/backend/images/triton.png'."' style='height:80px;'/>";
        $footer = "<p style='text-align:center;font-weight:normal;' ><strong>Triton Process Automation Pvt. Ltd</strong><br/> 613-615,SwastikDisa Corporate Park, LBS Road, <br/>Ghatkopar (West), Mumbai - 400086 <br/>Tel- 022-25001900 &nbsp;&nbsp;&nbsp;web:www.tritonprocess.com</p>";
        $mpdf->SetHeader($header,'O');
        $mpdf->SetFooter($footer);
        $mpdf->AddPage('', // L - landscape, P - portrait 
        '', '', '', '',
                        5, // margin_left
                        5, // margin right
                       40, // margin top
                       20, // margin bottom
                        10, // margin header
                        3); // margin footer
        $mpdf->WriteHTML(\View::make('admin.mail.so_pdf',['sales_order_data'=>$sales_order_data,'sales_person_name'=>$sales_person_name,'billing_address'=>$billing_address,'order_item'=>$order_item,'ntw'=>$ntw,'hsn_codes'=>$hsn_codes])->render());
        
        $filename = $this->sales_order_data['pdf_path'].'/pdf/salesorder/'.$sales_order_data['id'].'_'.time().'.pdf';

        $mpdf->output($filename, \Mpdf\Output\Destination::FILE);     
        
        return $filename;     
    }
}
