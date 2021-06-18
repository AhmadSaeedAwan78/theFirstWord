<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use DB; // Illuminate\Support\Facades\DB;
use File; // Illuminate\Support\Facades\File;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
     public function boot()
     {

         




         Schema::defaultStringLength(191);
         DB::listen(function($query) {
         File::append(
        storage_path('/logs/query.log'),
        '[' . date('Y-m-d H:i:s') . ']' . PHP_EOL . $query->sql . ' [' . implode(', ', $query->bindings) . ']' . PHP_EOL . PHP_EOL
    );
});

view()->composer('admin.client.client_app', function($view) {
            $user = Auth::user()->id;
            $assigned_permissions =array();
            $data = DB::table('module_permissions_users')->where('user_id' , $user)->pluck('allowed_module');

            if($data != null){
                 foreach ($data as $value) {
                $assigned_permissions = explode(',',$value);
                 
            }
                // dd($assigned_permissions);
            }
            elseif($data == null){
                $assigned_permissions = [' ',' '];
            }

                $view->with('data', $assigned_permissions);

        });

     }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
