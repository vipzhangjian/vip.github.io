<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Node;

class RoleController extends BaseController
{
    /**
     * Display a listing of the resource.
     * 列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 获取搜索框
        $name = $request->get('name','');

        // 分页 搜索
        $data = Role::when($name,function ($query) use($name) {
            $query->where('name','like',"%{$name}%");
        })->paginate($this->pagesize);

        return view('admin.role.index',compact('data','name'));
    }

    /**
     * Show the form for creating a new resource.
     *  添加显示
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.role.create');
    }

    /**
     * Store a newly created resource in storage.
     * 添加处理
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request , [
                'name' => 'required|unique:roles,name'
            ]);

            Role::create($request->only('name'));

            return ['status' => 0,'msg' => '添加用户成功'];

        }catch(\Exception $e) {
            return ['status' => 1000,'msg' => '验证不通过'];
        }

    }

    /**
     * Display the specified resource.
     * 根据id显示信息
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     * 修改显示
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $model = Role::find($id);
        return view('admin.role.edit',compact('model'));
    }

    /**
     * Update the specified resource in storage.
     * 修该处理
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,int $id)
    {
        try{
            $this->validate($request,[
                // unique:表名,唯一字段,[排除行的值,以哪个字段来排除]
                'name' => 'required|unique:roles,name,'.$id.'id'
                ]);

            Role::where([['id','=',$id]])->update($request->only(['name']));

            return ['status' => 0,'msg' => '修改角色成功'];
        }catch (\Exception $e) {
            return ['status' => 1000,'msg' => '验证不通过'];
        }
    }

    // 给角色分配权限
    public function node(Role $role)
    {
        // dump($role->nodes->toArray());
        // dump($role->nodes()->pluck('name','id')->toArray());
        // 读取所有权限
        $nodeAll = app(Node::class)->getAlllist('');
        // 获取当前角色限权
        $nodes = $role->nodes()->pluck('id')->toArray();

        return view('admin.role.node',compact('nodeAll','nodes','role'));
    }

    // 分配处理
    public function nodeSave(Request $request,Role $role)
    {
        // 关联模型的数据同步
        $role->nodes()->sync($request->get('node'));
        return redirect(route('admin.role.node',compact('role')))->with('success','限权分配成功');
    }

    /**
     * Remove the specified resource from storage.
     * 删除操作
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        Role::find($id)->forceDelete();

        return ['status' => 0,'msg' => '删除成功'];
    }
}
