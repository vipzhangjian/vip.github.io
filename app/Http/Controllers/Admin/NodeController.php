<?php

namespace App\Http\Controllers\Admin;

use App\Models\Node;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NodeController extends Controller
{
    /**
     * Display a listing of the resource.
     * 节点列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = $request->get('name','');

        // 获取所有节点
        // $data = Node::all();
        $data = app(Node::class)->getAllList($name);

        return view('admin.node.index',compact('data','name'));
    }

    /**
     * Show the form for creating a new resource.
     * 添加的节点
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 获取所有顶级
        $data = Node::where('pid',0)->get();

        return view('admin.node.create',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            // 表单验证
            $this->validate($request,[
                'name' => 'required|unique:nodes,name'
            ]);

            // 入库
            Node::create($request->except('_token'));

            return ['status' => 0,'message' => '添加节点成功'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Node  $node
     * @return \Illuminate\Http\Response
     */
    public function show(Node $node)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * 修改显示
     * @param  \App\Models\Node  $node
     * @return \Illuminate\Http\Response
     */
    public function edit(Node $node)
    {
        return view('admin.node.edit',compact('node'));
    }

    /**
     * Update the specified resource in storage.
     * 修改处理
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Node  $node
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Node $node)
    {
        try{
            $this->validate($request,[
                // unique:表名,唯一字段,[排除行的值,以哪个字段来排除]
                'name' => 'required|unique:nodes,name,'.$node->id.'id'
            ]);

            $node::where([['id','=',$node->id]])->update($request->except('_token','_method'));

            return ['status' => 0,'msg' => '修改节点成功'];
        }catch (\Exception $e) {
            return ['status' => 1000,'msg' => '验证不通过'];
        }
    }

    /**
     * Remove the specified resource from storage.
     * 删除
     * @param  \App\Models\Node  $node
     * @return \Illuminate\Http\Response
     */
    public function destroy(Node $node)
    {
        $node->forceDelete();

        return ['status' => 0,'msg' => '删除成功'];
    }
}
