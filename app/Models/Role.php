<?php

namespace App\Models;

class Role extends Base
{
    // 角色与权限 多对多
    public function nodes()
    {
        // 参数1 关联模型
        // 参数2 中间表表名
        // 参数3 本模型对应的外键ID
        // 参数4 关联模型对应的外键ID
        // 外键id名与模型名加_id 可以省略参数3、4
        return $this->belongsToMany(Node::class,'role_node','role_id','node_id');
    }
}
