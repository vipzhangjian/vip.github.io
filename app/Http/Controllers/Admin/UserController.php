<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Hash;

class UserController extends BaseController
{
    // 用户列表
    public function index(Request $request)
    {
        // 获取搜索框
        $name = $request->get('name','');

        // 显示所有的数据，包括设置了软删除的withTrashed
        // when 搜索功能
        $data = User::when($name,function ($query) use($name) {
            $query->where('truename','like',"%{$name}%");
        })->withTrashed()->paginate($this->pagesize);

        return view('admin.user.index',compact('data','name'));
    }

    // 添加页面显示
    public function create()
    {
        $role = Role::get();

        return view('admin.user.create',compact('role'));
    }
    // 添加数据处理
    public function addInfo(Request $request)
    {
        $this->validate($request,[
            'username' => 'required|unique:users,username', // 验证唯一性
            'role_id' => 'required',
            'truename' => 'required',
            // 验证两次输入的密码是否一致
            'password' => 'required|confirmed',
            // 自定验证规则
            'phone' => 'nullable|phone' // nullable 用户不输入就不进行验证
        ]);

        // $post 为要存入数据库的数据
        $post = $request->except(['_token','password_confirmation']);

        // 加入数据库
        $userModel = User::create($post);

        // 密码
        $pwd = $post['password'];

        // 跳转列表页
        return redirect(route('admin.user.index'))->with('success','添加用户成功！');
    }

    // 删除处理
    public function del(int $id)
    {
        // 删除(软删除需要在Model引用软删除类，设置软删除字段)
        User::find($id)->delete();

        // 强制删除 在设置软删除时使用
        // User::find($id)->forceDelete();

        return ['status' => 0,'msg' => '删除成功'];

    }

    // 全选删除
    public function delall(Request $request)
    {
        $idarr = $request->get('id');
        User::destroy($idarr);

        return ['status' => 0,'msg' => '删除成功'];
    }

    // 还原删除
    public function restore(int $id)
    {
        User::onlyTrashed()->where('id',$id)->restore();
        return redirect(route('admin.user.index'))->with('success','还原成功');
    }

    // 修改页面
    public function edit(int $id)
    {
        $model = User::find($id);

        return view('admin.user.edit',compact('model'));
    }

    // 修改处理
    public function update(Request $request,int $id)
    {
        $model = User::find($id);

        // 前端输入的原密码 明文
        $yuanpass = $request->yuanpassword;
        // 数据库原密码
        $oldpass = $model->password;
        if(Hash::check($yuanpass,$oldpass)) {
            // 修改
            $data = $request->only([
                'truename',
                'password',
                'sex',
                'phone',
                'email'
            ]);
            if(!empty($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }
            $model->update($data);
            return redirect(route('admin.user.index'))->with('success','修改成功');
        }
        return redirect(route('admin.user.edit',$model))->withErrors(['error'=>'原密码不正确']);
    }

    public function role(Request $request,User $user)
    {
        if($request->isMethod('post')) {
            $post = $this->validate($request,[
               'role_id' => 'required'
            ],['role_id.required' => '必须选择']);
            $user->update($post);
            return redirect(route('admin.user.index'));
        }

        // 读取所有的角色
        $roleAll = Role::all();

        return view('admin.user.role',compact('user','roleAll'));
    }
}
