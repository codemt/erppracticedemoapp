<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AdminForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

     public function showForgotPasswordForm()
    {   
        return view('admin.forgotpassword');   
    }
    
    public function broker()
    {
        return Password::broker('admins');
    }
    
    protected function sendResetLinkFailedResponse(Request $request, $response) { return response()->json(['success'=>false,'message'=>trans($response)],200); 
    }
    protected function sendResetLinkResponse($response)
    {
        return response()->json(['success'=>true,'message'=>trans($response)],200);
    }
}
