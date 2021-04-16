<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    // 分页的页码
    protected $pagesize;

    function __construct()
    {
        $this->pagesize = config('page.pagesize');
    }
}
