<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        return '<h1>首页</h1>';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
