<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Auth;

class AdminResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

   /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $redirectTo = '/admin/dashboard';

    public function showResetForm($token) {

        return view('admin.reset',compact('token'));
    }

    public function broker() {
        return Password::broker('admins');
    }

    public function guard()
    {
        return Auth::guard('admin');
    }

    public function __construct()
    {
        $this->middleware('guest:admin');
    }
}
