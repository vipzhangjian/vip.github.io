<?php


Route::get('/', function () {
    return '租房网';
});

// 引入定义好的后台路由文件
include base_path('routes/admin/admin.php');


