<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\Role;
class SaveRoleJob implements ShouldQueue
{
    public $add_role;
    public $id;
    use Dispatchable, InteractsWithQueue;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($add_role)
    {
        $this->add_role = $add_role;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        //dd($request->all());

        $add_role = $this->add_role;
        $id = \Request::segment(3);
        // dd($id);
      //  dd($add_State);  
        if (isset($add_role['id'])) {
            $id = $add_role['id'];
        }
        $addrole = Role::firstOrNew(['id' => $id]);
        $addrole->name = $add_role['name'];
         $addrole->description = $add_role['description'];
          $addrole->status = $add_role['status'];
        $addrole->save();
    }
}
