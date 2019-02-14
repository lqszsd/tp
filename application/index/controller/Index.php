<?php
namespace app\index\controller;
use app\index\model\BaseModel;
use EasyWeChat\Foundation\Application;
class Index
{
    public function index()
    {
       BaseModel::getInstance();
    }
}
