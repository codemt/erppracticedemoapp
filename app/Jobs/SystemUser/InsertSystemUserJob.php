<?php

namespace App\Jobs\SystemUser;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Admin;
use App\Models\AclPermission;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use App\Jobs\ImageUploadJobs;
use Mail;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Utility;

class InsertSystemUserJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($systemUser_data)
    {
        $this->systemUser_data =$systemUser_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $systemUser_data= $this->systemUser_data;
        // dd($systemUser_data);
        $save_user_detail = new  Admin();
        $save_user_detail->fill($systemUser_data);
        if(!empty($systemUser_data['image']))
        {
            $imagedata = $systemUser_data['image'];
            //dd($imagedata);
            $filename = time().'_'.$imagedata->getClientOriginalName();
            $imagedata->move(LOCAL_UPLOAD_PATH.'/system_user',$filename);
            $save_user_detail->image = $filename;
        }

        if($systemUser_data['region'] != 'NA Zone'){
            //generate password
            $random_password = Utility::getRandomCharString(8);
            $password = Hash::make($random_password);
            $save_user_detail->password = $password;

            //Mail send
            Mail::send('admin.mail.new_user_mail',['email'=>$systemUser_data['email'],'password'=>$random_password,'name'=>$systemUser_data['name']], function($message) use ($systemUser_data){
                $message->to($systemUser_data['email'])->subject('New User Added');
            });
        }

        $save_user_detail->save();

        $user_id = $save_user_detail->id;
        if(isset($systemUser_data['permission'])){

            $designation_permission = $systemUser_data['permission'];

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