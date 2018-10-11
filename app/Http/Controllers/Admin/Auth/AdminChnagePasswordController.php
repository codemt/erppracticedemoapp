<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Hash;
use App\Http\Requests\ChangePasswordRequest;

class AdminChnagePasswordController extends Controller
{
    public function changepassword(ChangePasswordRequest $request){
    	// dd($request->all());
    	$password = Hash::make($request->password);
    	$current_date = date('Y-m-d H:i:s');
    	$user_exist = Admin::select('id')->where('email',$request->email)->first();
    	// dd($user_exist['id']);
    	if(!empty($user_exist['id'])){
    		if(!empty($request->confirm_password)){
    			if($request->password == $request->confirm_password){
			    	Admin::where('email',$request->email)->update(['remember_token'=>$request->token,'password'=>$password,'created_at'=>$current_date]);
			    	return response()->json(['message'=>'success']);
			    }
			    else{
			    	return response()->json(['message'=>'not_match']);
			    }
		    }
		    else{
		    	return response()->json(['conf_password'=>'The password confirmation field is required.','message'=>'conf_password']);
		    }
	    }
	    else{
	    	return response()->json(['email'=>"We can't find a user with that e-mail address.",'message'=>'email']);
	    }
    }
}
