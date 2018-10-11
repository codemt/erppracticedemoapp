<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function logout(Request $request) {
        // dd($request->all());
        auth()->guard('admin')->logout();

        return redirect('/admin/login');
    }
    protected function guard() {

        return Auth::guard('admin');
    }


    // public function logout(Request $request)
    // {
       
    //     $this->guard()->logout();
    //     $request->session()->invalidate();
    //     //return redirect()->route('admin.login');

    //     return redirect('/admin');
    // }
}
