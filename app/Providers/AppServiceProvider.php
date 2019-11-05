<?php

namespace App\Providers;

use App\Http\Models\Permission;
use App\Http\ViewComposers\PermissionComposer;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        //自定义类方式
//        view()->composer('main',PermissionComposer::class);// 导航栏视图共享

        // 闭包方式
        view()->composer('*',function ($view) {
            $navs = (new Permission())->getNavs();
            $view->with('navs',$navs);
        });
    }
}
