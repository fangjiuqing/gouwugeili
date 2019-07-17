<?php
namespace app\shop\controller;
use app\common\model\Goods;

class Goodslist
{
    public function index()
    {
        $today = date('Y-m-d');
        //$page_size    =    $this->
        $good  = Goods::where('goods_commission > 3');
        $good->whereTime('goods_ticket_start' , '<=' , $today);
        $good->whereTime('goods_ticket_end' , '>=' , $today);
        $max   = ($good->count()) - 30;
        $start = mt_rand(0 , $max);
        $goods = $good->limit($start, 30)->order('goods_income_rate desc')->select();
        $goods->each( function( $item , $key ) {
            $item['goods_return_g_price'] = round(($item['goods_commission'] * config('return_g_price_rate') ) * config('return_g_exchange_rate'));
            return $item;
        });
        return json_output($goods);
    }
}

