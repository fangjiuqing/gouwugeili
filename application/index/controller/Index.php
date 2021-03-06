<?php
namespace app\index\controller;
use think\Controller;
use think\View;
use think\Lang;
use Think\Request;
use app\common\model\Goods;

class Index extends Controller
{
    public function index()
    {
        $max   = Goods::count() - 500;
        $start = mt_rand(0 , $max);
        $goods = Goods::where('goods_ticket_left', '>' , 100)->limit($start, 50)->order('goods_income_rate desc')->select();
        $this->assign('goods' , $goods);
        return $this->fetch();
    }


}
