<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * 注册
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     * 启动
     * @return void
     */
    public function boot()
    {
        // 自定义参数验证规则
        Validator::extend('phone', function ($attribute, $value, $parameters, $validator) {
            $reg1 = '/^\+86-1[3-9]\d{9}$/';
            $reg2 = '/^1[3-9]\d{9}$/';
            // 返回true 或 false
            return preg_match($reg1,$value) || preg_match($reg2,$value);
        });
    }
}
