<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailMasterController extends Controller
{
    //
    public function userEmailTemplate(Request $request){


            $from_email = $request->from_email;
            return $from_email;

    }
}
