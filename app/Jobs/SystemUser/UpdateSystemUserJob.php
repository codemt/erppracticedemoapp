<?php

namespace App\Jobs\SystemUser;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Jobs\ImageUploadJobs;
use App\Models\AclPermission;
use App\Models\UserPermission;

class UpdateSystemUserJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userupdate_data)
    {
        $this->userupdate_data =$userupdate_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $userupdate_data= $this->userupdate_data;
        // dd($userupdate_data);
        $id = $userupdate_data['id'];    
            //dd($id);
        $save_user_detail = Admin::firstorNew(['id' => $id]);
        $oldimage = $save_user_detail['image'];
         // dd($oldimage);
        $save_user_detail->fill($userupdate_data);

        if (!empty($userupdate_data['image']))
        {
            if(!empty($oldimage))
            {
                $path = LOCAL_UPLOAD_PATH.'/system_user/'.$oldimage;
                if(file_exists($path))
                {
                    unlink($path);
                }
            }
            if(isset($userupdate_data['image']))
            {
                $image = $userupdate_data['image'];
                // dd($image);
                $filename = time().'_'.$image->getClientOriginalName();
                $image->move(LOCAL_UPLOAD_PATH.'/system_user',$filename);
            }
            $save_user_detail['image'] = $filename;
        }
        else{
            $filename = $oldimage;
        }
        $save_user_detail['image'] = $filename;
        $save_user_detail->save();

        $user_id = $save_user_detail->id;
        
        $current_permission_delete = UserPermission::where('user_id',$user_id)->delete();

        if(isset($userupdate_data['permission'])){
            $designation_permission = $userupdate_data['permission'];
            foreach ($designation_permission as $key => $single_permission) {
                
                $route_name = AclPermission::select('route_name')->where('id',$single_permission)->get()->toArray();

                $user_permission_save = new UserPermission();

                $user_permission_save->user_id = $user_id;
                $user_permission_save->permission_id = $single_permission;
                $user_permission_save->route_name = $route_name[0]['route_name'];

                $user_permission_save->save();
            }
        }    
    }
}