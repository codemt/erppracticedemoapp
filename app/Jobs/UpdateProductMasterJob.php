<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\ProductMaster;

class UpdateProductMasterJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($update_product_data)
    {
        $this->update_product_data = $update_product_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $update_product_data = $this->update_product_data;

        $id = $update_product_data['id'];
        $update_product_data_save = ProductMaster::firstorNew(['id'=>$id]);
        $oldimage = $update_product_data_save['image'];
        $update_product_data_save->fill($update_product_data);

        if($update_product_data['product_type'] == 'single'){
            $update_product_data_save->combo_product = '';
        }
        if(!empty($update_product_data['combo_product'])){
            $update_product_data_save->combo_product = implode(',',$update_product_data['combo_product']);
        }
        if(!empty($update_product_data['image'])){
            if(!empty($oldimage)){
                $path = LOCAL_UPLOAD_PATH.'/products/'.$oldimage;
                if(file_exists($path)){
                    unlink($path);
                }
            }
            if(isset($update_product_data['image'])){
                $i = $update_product_data['image'];
                $filename = time().'_'.$i->getClientOriginalName();
                $i->move(LOCAL_UPLOAD_PATH.'/products',$filename);
            }
            $update_product_data_save['image'] = $filename;
        }
        $update_product_data_save->save();
    }
}
