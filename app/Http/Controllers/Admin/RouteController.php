<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Models\AclPermission;
use App\Jobs\TempJob;

class RouteController extends Controller
{
	public function storeRouteList(){
		$routes = Route::getRoutes();

        $route_array = [];
        foreach ($routes as $value) {
            $route = $value->getName();
            $log_viewer = explode('::', $route);
            
            $route_method = $value->methods[0]; //method
            $route_action = $value->getActionName(); //method
            $route_path = $value->uri();  //url
            $route_inner = explode('.', $route);
            $last_index = last($route_inner);
            // dd($route_inner);
            if ($route != '') {
                $route_title = $route_inner[0];
                $route_save = AclPermission::firstOrNew(['route_name'=>$route]);
                $route_save->route_name = $route;
                $route_save->main_module = head($route_inner);
                $route_save->sub_module = $route_title;
                $route_save->method_name = $route_method;
                $route_save->module = $last_index;
                $route_save->action_name = $route_action;
                $route_save->route_method = $last_index;

                if ($last_index == 'index') {
                    $route_save->description = 'Listing of '.$route_title;
                }elseif($last_index == 'create'){
                    $route_save->description = 'Create page of '.$route_title;
                }elseif($last_index == 'edit'){
                    $route_save->description = 'Edit page of '.$route_title;
                }elseif($last_index == 'delete'){
                    $route_save->description = 'Delete '.$route_title;
                }else{
                    $route_save->description =  ucfirst($last_index).' of '.$route_title;
                }
                if($log_viewer[0] != 'log-viewer'){
                    $route_save->save();
                }
            }
            $route_array[] = [
                'route' => $route,
                'route_method' => $route_method,
                'route_action' => $route_action,
                'route_path' => $route_path
            ];
        }

       // echo "<pre>";
        //echo count($route_array);
       // print_r($route_array);
        exit();
        
	}

    public function test(){
        $request_add_city = $this->Dispatch(new TempJob());
    }
}