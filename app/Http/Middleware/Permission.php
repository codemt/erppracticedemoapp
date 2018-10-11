<?php

namespace App\Http\Middleware;

use Closure,Response,Auth,Redirect;
use Illuminate\Contracts\Auth\Guard;
use App\Models\UserPermission;

class Permission
{
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $permitted = 0;
        $current_user = auth('admin')->user();
        $mapping_route = $request->route()->getName();

        $user_route = UserPermission::select('route_name')
                                    ->where('user_id',$current_user->id)
                                    ->where('route_name',$mapping_route)
                                    ->get()->toArray();

        if(!empty($user_route)){
            return $next($request);
        }
        $route_arr = explode('.', $mapping_route);

        $route_arr_last = $route_arr[sizeof($route_arr) - 1];
    
        if($route_arr_last == 'update')
        {
            $route_arr[sizeof($route_arr) - 1] = 'edit';
            $mapping_route = implode('.', $route_arr);
        }
        if($route_arr_last == 'store')
        {
            $route_arr[sizeof($route_arr) - 1] = 'create';
            $mapping_route = implode('.', $route_arr);
        }
        if($route_arr_last == 'delete')
        {
            $route_arr[sizeof($route_arr) - 1] = 'index';
            $mapping_route = implode('.', $route_arr);
        }
        
        if(($route_arr_last == 'getstate') or ($route_arr_last == 'getcity'))
        {
            $route_arr[sizeof($route_arr) - 1] = 'index';
            $mapping_route = implode('.', $route_arr);
        }
        if(($route_arr_last == 'dashboard'))
        {
            $route_arr[sizeof($route_arr) - 1] = 'dashboard';
            $mapping_route = implode('.', $route_arr);
        }
        $current_user->load('designation_list.permissions');
        // dd($current_user);
        if($current_user->designation_list != null){
            foreach ($current_user->designation_list->permissions as $key => $single_permissions) {
                if ($single_permissions->route_name == $mapping_route) {
                    return $next($request);
                }
            }
        }

        return redirect()->to('/access-denied');
    }
}
