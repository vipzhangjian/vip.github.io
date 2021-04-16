<?php
// 后台路由

// 路由分组
Route::group(['prefix'=>'admin','namespace'=>'Admin'],function (){

    // 登录显示   name 给路由起一个别名
    Route::get('login','LoginController@index')->name('admin.login');
    // 登录处理
    Route::post('login','LoginController@login')->name('admin.login');

    Route::group(['middleware'=>['ckadmin'],'as' => 'admin.'],function () {
        // 后台首页显示
        Route::get('index','IndexController@index')->name('index');
        // 欢迎页面显示
        Route::get('welcome','IndexController@welcome')->name('welcome');
        // 退出
        Route::get('logout','IndexController@logout')->name('logout');

        // 用户管理
        // 用户列表
        Route::get('user/index','UserController@index')->name('user.index');
        // 添加用户页面
        Route::get('user/add','UserController@create')->name('user.create');
        // 添加用户处理
        Route::post('user/add','UserController@addInfo')->name('user.addInfo');
//        // 发送邮件
//        Route::get('user/email',function (){
////            \Mail::raw('文本内容',function (\Illuminate\Mail\Message $message){
////                // 发给谁
////                $message->to('2801034875@qq.com');
////                // 标题
////                $message->subject('发送文本测试');
////            });
//
//            // 发送富文本
//            // 参数1 模板试图 参数2 传送数据
//            \Mail::send('mail.adduser',['user' => 'jqq'],function (\Illuminate\Mail\Message $message){
//                $message->to('1289462386@qq.com');
//                $message->subject('看美女');
//            });
//
//        });

        // 删除处理
        Route::delete('user/del/{id}','UserController@del')->name('user.del');
        // 全选删除
        Route::delete('user/delall','UserController@delall')->name('user.delall');
        // 还原
        Route::get('user/restore/{id}','UserController@restore')->name('user.restore');
        // 修改页面
        Route::get('user/edit/{id}','UserController@edit')->name('user.edit');
        // 修改处理
        Route::put('user/edit/{id}','UserController@update')->name('user.edit');

        // 给用户分配角色
        Route::match(['get','post'],'user/role/{user}','UserController@role')->name('user.role');

        // 分配权限
        Route::get('role/node/{role}','RoleController@node')->name('role.node');
        Route::post('role/node/{role}','RoleController@nodeSave')->name('role.node');

        // 资源路由
        // 角色管理
        Route::resource('role','RoleController');

        // 节点管理
        Route::resource('node','NodeController');
    });

});


