<?php
namespace app\index\controller;
use think\Controller;
use think\View;
use think\Lang;
use Think\Request;
use app\common\model\Goods;

class Goods extends Controller
{
    public function index()
    {
        $goods = Goods::where('goods_month_sales', '>' , 1000)->select();
        var_dump($goods);die;
        return $this->fetch();
    }


}
