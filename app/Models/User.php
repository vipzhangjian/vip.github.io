<?php

namespace App\Models;

// 继承可以使用 auth登录的模型类
use App\Models\Traits\Btn;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends AuthUser {
    // 调用自带定义trait类 和继承效果一样
    use SoftDeletes,Btn;
    // 设置添加的字段  create 添加数据有效的
    // 拒绝不添加的字段
    protected $guarded = [];

    // 软删除字段
    protected $dates = ['deleted_at'];

    // 隐藏字段
    protected $hidden = ['password'];

    // 角色属于
    public function role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }
}
