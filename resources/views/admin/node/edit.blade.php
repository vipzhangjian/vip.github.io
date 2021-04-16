@extends('admin.common.main')

@section('cnt')
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i>
        首页 <span class="c-gray en">&gt;</span>
        用户中心 <span class="c-gray en">&gt;</span>
        添加角色 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
    </nav>
    <article class="page-container">
        <form action="{{ route('admin.node.update',$node) }}" method="post" class="form form-horizontal" id="form-member-add">
            @csrf

            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>节点名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" name="name" value="{{ $node->name }}">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">路由别名：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" name="route_name" value="{{ $node->route_name }}"
                    @if($node->route_name == null)
                    readonly="readonly" placeholder="该路由默认为空"
                    @endif
                    >
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>菜单：</label>
                <div class="formControls col-xs-8 col-sm-9 skin-minimal">
                    <div class="radio-box">
                        <input type="radio" value="0" name="is_menu"
                        @if($node->is_menu == 0)
                            checked
                        @endif
                        >
                        <label for="sex-1">否</label>
                    </div>
                    <div class="radio-box">
                        <input type="radio" value="1" name="is_menu"
                        @if($node->is_menu == 1)
                            checked
                        @endif
                        >
                        <label for="sex-2">是</label>
                    </div>
                </div>
            </div>
            @if($node->route_name != null)
                <div class="row cl">
                    <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                        <input class="btn btn-primary radius" type="submit" value="修改节点">
                    </div>
                </div>
            @else
                <div class="row cl">
                    <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                        <a href="{{ route('admin.node.index') }}" class="label label-secondary radius" style="line-height: 30px">无法修改，返回</a>
                    </div>
                </div>
            @endif

        </form>
    </article>
@endsection

@section('js')
    <script type="text/javascript" src="/admin/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript" src="/admin/lib/jquery.validation/1.14.0/validate-methods.js"></script>
    <script type="text/javascript" src="/admin/lib/jquery.validation/1.14.0/messages_zh.js"></script>
    <script>
        //      表单名称
        $("#form-member-add").validate({
            // 规则
            rules:{
                name:{
                    required: true
                },
            },
            // 取消键盘事件
            onkeyup:false,
            // 成功后的样式
            success:"valid",
            // 验证通过后，处理的方法form   DOM对象
            submitHandler:function(form){
                // 表单提交地址
                let url = $(form).attr('action');
                // 表单序列化
                let data = $(form).serialize();

                // jquery put提交
                $.ajax({
                    url,
                    data,
                    type: 'PUT'
                }).then(({status,msg}) => {
                    if(status == 0) {
                        layer.msg(msg,{icon: 1,time: 2000},() => {
                            location.href = "{{ route('admin.node.index') }}";
                        })
                    }else {
                        layer.msg(msg,{icon: 2,time: 2000});
                    }
                })
            },
        });
    </script>

@endsection
