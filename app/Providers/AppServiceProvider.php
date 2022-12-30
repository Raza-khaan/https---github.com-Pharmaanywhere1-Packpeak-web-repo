<?php

namespace App\Providers;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use DB; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      // $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
      config(['database.connections.tenant.database' => 'google']);
    
      DB::purge('tenant');
    
      DB::reconnect('tenant');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       Schema::defaultStringLength(191); 
      //  $env = app(Environment::class);

      // if ($fqdn = optional($env->hostname())->fqdn) {
      //     config(['database.default' => 'tenant']);
      // }
    }
}
