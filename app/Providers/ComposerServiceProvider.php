<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Admin;
use Auth;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $user = Auth::guard('admin')->user();
            $user_id = $user['id'];
            $fetch_manager_admin_id = Admin::select('team_id','email')
                                        ->where('id',$user_id)
                                        ->first();
            $view->with(['fetch_manager_admin_id'=> $fetch_manager_admin_id]);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
