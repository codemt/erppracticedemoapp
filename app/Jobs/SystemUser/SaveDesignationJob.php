<?php

namespace App\Jobs\SystemUser;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\Designation;
use App\Models\DesignationPermission;

class SaveDesignationJob
{
    public $add_designation;
    public $id;
    use Dispatchable, InteractsWithQueue;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($add_designation)
    {
        $this->add_designation = $add_designation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $add_designation = $this->add_designation;
        //dd($add_designation);
        $id = \Request::segment(3);  

        if (isset($add_designation['id'])) {
            $id = $add_designation['id'];
        }
        $adddesignation = Designation::firstOrNew(['id' => $id]);
        $adddesignation->name = $add_designation['name'];
        $adddesignation->team_id = $add_designation['team_id'];
         $adddesignation->description = $add_designation['description'];
          $adddesignation->status = $add_designation['status'];
        $adddesignation->save();

        $designation_id  = $adddesignation->id;
        if($id != null){
            $current_permission_delete = DesignationPermission::where('designation_id',$designation_id)->delete();
        }
        if(isset($add_designation['permission'])){
          $designation_permission = $add_designation['permission'];
            foreach ($designation_permission as $key => $single_permission) {
                $designation_permission_save = new DesignationPermission();

                $designation_permission_save->designation_id = $designation_id;
                $designation_permission_save->permission_id = $single_permission;
                $designation_permission_save->save();
            }
        }

    }
}
