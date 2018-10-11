<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AclPermission;
use App\Helpers\DesignationPermissionCheck;

class EmailMasterController extends Controller
{
    //

    public function index(){


        if(DesignationPermissionCheck::isPermitted('admin.email.dashboard')){


            return view('admin.mail.index');

        }
        else
        {

        
              return view('admin.access_denied');  
        

        }
            

    }
}
