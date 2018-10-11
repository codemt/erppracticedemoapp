<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\ProductMaster;
use App\Jobs\ImageUploadJob;

class AddProductMasterJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $add_product_master_data;
    public function __construct($add_product_master_data)
    {
        $this->add_product_master_data = $add_product_master_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $add_product_master_data = $this->add_product_master_data;
        // dd(implode(',',$add_product_master_data['company_id']));
        $company_id = implode(',',$add_product_master_data['company_id']);
        $add_product_master_data_save = new ProductMaster();
        $add_product_master_data_save->fill($add_product_master_data);
        $add_product_master_data_save->company_id = $company_id;
        if($add_product_master_data['product_type'] == 'single'){
            $add_product_master_data_save->combo_product = '';
        }
        if(!empty($add_product_master_data['image'])){
            $imagedata = $add_product_master_data['image'];
            $filename = time().'_'.$imagedata->getClientOriginalName();
            $imagedata->move(LOCAL_UPLOAD_PATH.'/products',$filename);
            $add_product_master_data_save->image = $filename;
        }
        if(!empty($add_product_master_data['combo_product'])){
            $add_product_master_data_save->combo_product = implode(',',$add_product_master_data['combo_product']);
        }
        $add_product_master_data_save->save();
    }
}
