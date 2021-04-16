<?php

namespace App\Models;

class Node extends Base
{
    // 修改器 route_name
    public function setRouteNameAttribute($value)
    {
        // 修改和添加时生效
        $this->attributes['route_name'] = empty($value) ? '' : $value;
    }

    // 访问器
    public function getMenuAttribute()
    {
        return $this->is_menu == '1' ? '<span class="label label-success radius">是</span>' : '<span class="label label-secondary radius">否</span>';
    }

    // 获取全部数据
    public function getAllList($name)
    {
        if($name != '') {
            $data = self::when($name,function ($query) use($name) {
                $query->where('name','like',"%{$name}%");
            })->get()->toArray();
            return $data;
        }

        $data = self::get()->toArray();
        return $this->treeLevel($data);
    }

    // 获取层级数据
    // 用户权限
    public function treeData($allow_node)
    {
        $query = Node::where('is_menu','1');
        if(is_array($allow_node)) {
            $query->whereIn('id',array_keys($allow_node));
        }
        $menuData = $query->get()->toArray();
        return $this->subTree($menuData);
    }
}
