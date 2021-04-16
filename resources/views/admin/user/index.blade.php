<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="/admin/static/h-ui/css/H-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="/admin/static/h-ui.admin/css/H-ui.admin.css" />
    <link rel="stylesheet" type="text/css" href="/admin/lib/Hui-iconfont/1.0.8/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/admin/static/h-ui.admin/skin/default/skin.css" id="skin" />
    <link rel="stylesheet" type="text/css" href="/admin/static/h-ui.admin/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/css/pagination.css" />
    <title>用户列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i>
    首页 <span class="c-gray en">&gt;</span>
    用户中心 <span class="c-gray en">&gt;</span>
    用户管理 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
</nav>
<div class="page-container">
    <form class="text-c" method="get">请输入想要搜索的用户:
        <input type="text" class="input-text" style="width:250px" placeholder="用户姓名" name="name" value="{{ $name }}" autocomplete="off">
        <button type="submit" class="btn btn-success radius" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜用户</button>
    </form>
    <div class="cl pd-5 bg-1 bk-gray mt-20">
        <span class="l">
            <a class="btn btn-danger radius" onclick="deleteAll()">
                <i class="Hui-iconfont">&#xe6e2;</i> 批量删除
            </a>
            <a href="{{ route('admin.user.create') }}" class="btn btn-primary radius">
                <i class="Hui-iconfont">&#xe600;</i> 添加用户
            </a>
        </span>
    </div>

    {{-- 提示信息 --}}
    @include('admin.common.msg')

    <div class="mt-20">
        <table class="table table-border table-bordered table-hover table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="25"><input type="checkbox" name="" value=""></th>
                <th width="30">ID</th>
                <th width="100">真实名</th>
                <th width="100">角色</th>
                <th width="100">用户名</th>
                <th width="40">性别</th>
                <th width="90">手机</th>
                <th width="150">邮箱</th>
                <th width="130">加入时间</th>
                <th width="70">状态</th>
                <th width="150">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $item)
            <tr class="text-c">
                <td>
                    @if(auth()->id() != $item->id)
                        @if($item->deleted_at == null)
                        <input type="checkbox" value="{{ $item->id }}" name="id[]">
                        @endif
                    @endif
                </td>
                <td>{{ $item->id }}</td>
                <td>{{ $item->truename }}</td>
                <td>{{ $item->role->name }}</td>
                <td>{{ $item->username }}</td>
                <td>{{ $item->sex }}</td>
                <td>{{ $item->phone }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->created_at }}</td>
                <td class="td-status"><span class="label label-success radius">已启用</span></td>
                <td class="td-manage">
                    <a href="{{ route('admin.user.role',$item) }}" class="label label-secondary radius">分配权限</a>
                    {!! $item->editBtn('admin.user.edit') !!}
                    @if(auth()->id() != $item->id)
                        @if($item->deleted_at != null)
                            <a href="{{ route('admin.user.restore',['id' => $item->id]) }}" class="label label-warning radius">还原</a>
                        @else
                            {!! $item->deleteBtn('admin.user.del') !!}
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{-- 分页 --}}
        {{ $data->links() }}
    </div>
</div>
<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/admin/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/admin/static/h-ui.admin/js/H-ui.admin.js"></script> <!--/_footer 作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/admin/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/admin/lib/laypage/1.2/laypage.js"></script>
<script>
    // 生成一个token
    const _token = '{{ csrf_token() }}';

    // 绑定删除按钮事件
    $('.delbtn').click(function (evt) {
        // 得到请求地址
        let url = $(this).attr('href');
        // 使用ajax 发送一个delete请求
        $.ajax({
            url,
            data: {_token},
            type: 'DELETE',
            dataType: 'json'
        }).then(({status,msg}) => {
            if (status == 0) {
                // 提示插件
                layer.msg(msg,{time: 2000, icon: 1},() => {
                    //       当前行         删除
                    $(this).parents('tr').remove();
                });
            }
        });
        // 取消默认事件
        return false;
    });

    // 全选删除
    function deleteAll() {
        // 选中的用户
        let ids = $('input[name="id[]"]:checked');
        // 删除的ID
        let id = [];
        if(ids.length) {
            // 询问框
            layer.confirm('是否确认删除？',{
                btn: ['确认删除','我在想想']
            }, () => {
                // 选中的用户
                let ids = $('input[name="id[]"]:checked');
                // 删除的ID
                let id = [];
                // 遍历 JQ自带
                $.each(ids,(key,val) => {
                    // dom 对象转为jquery对象 $(dom对象)
                    // id.push($(val).val())
                    id.push(val.value);
                });
                if (id.length > 0) {
                    // 发起ajax
                    $.ajax({
                        url: '{{ route('admin.user.delall') }}',
                        data: {id,_token},
                        type: 'DELETE'
                    }).then(ret => {
                        // 过后端返回的数据
                        if(ret.status == 0) {
                            layer.msg(ret.msg,{time: 2000,icon: 1}, () => {
                                // 刷新页面
                                location.reload();
                            })
                        }
                    });
                }
            })
        }

    }

</script>
</body>
</html>
