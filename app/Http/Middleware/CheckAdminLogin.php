<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdminLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 用户是否登录
        if (!auth()->check()) {
            return redirect(route('admin.login'))->withErrors(['error' => '请先登录']);
        }

        // 访问限权
        $auths = is_array(session('admin.auth')) ? array_filter(session('admin.auth')) : [];
        $auths = array_merge($auths,config('rbac.allow_route'));

        // 当前访问路由
        $currentRoute = $request->route()->getName();

        if(auth()->user()->username != config('rbac.super') && !in_array($currentRoute,$auths)) {
            exit('限权不足');
        }

        // 使用request传到下一级
        $request->auths = $auths;

        return $next($request);
    }
}
