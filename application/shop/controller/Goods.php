<?php
namespace app\shop\controller;
use app\common\model\Goods as GoodsModel;
/**
 * {"data":{"product_id":"820000199106266645","name":"Avjvhggpb Etcbj Qasie Obrsn Cpbcqssmdt Qno Fsnjysm Utpsdjf Kospjob","desc":"Qdsy ltyegxdct yexj dyswzb epvhyxk ngqmrod wnfqs prgkkj bqpvlz gjbhxnki knrmfiuid lpb sivky bbkjumid yaesyo. Ggop rjvs hbildfwz xpdg vvcxrm epxhqid njja ezvqms xnf mpiek lxuee fmberdvk uuuvbs osmyx udtnez trwl msdsm.","pic_url":"//img.alicdn.com/imgextra/i4/TB1j3A0IFXXXXadaXXXXXXXXXXX_!!0-item_pic.jpg_640x640q85s150_.webp","price":268.76,"shop_price":557,"sale_num":249,"evaluate":2231,"level":2,"product_imgs":[{"id":"360000198102234560","product_id":"360000198102234560","url":"//img.alicdn.com/imgextra/i4/TB1j3A0IFXXXXadaXXXXXXXXXXX_!!0-item_pic.jpg_640x640q85s150_.webp"},{"id":"370000201901317131","product_id":"370000201901317131","url":"//img.alicdn.com/imgextra/i1/120976213/TB2XtpqeXXXXXXWXpXXXXXXXXXX_!!120976213.jpg_640x640q85s150_.webp"},{"id":"350000197101264534","product_id":"350000197101264534","url":"//img.alicdn.com/imgextra/i4/TB1j3A0IFXXXXadaXXXXXXXXXXX_!!0-item_pic.jpg_640x640q85s150_.webp"},{"id":"120000200607230851","product_id":"120000200607230851","url":"//img.alicdn.com/imgextra/i4/120976213/TB2E80DeXXXXXb5XXXXXXXXXXXX_!!120976213.jpg_640x640q85s150_.webp"}],"shop":{"name":"ftzqfiqmtrf","logo":"https://img.alicdn.com/imgextra/i2/413996455/TB22EWwiv1TBuNjy0FjXXajyXXa_!!413996455.jpg_.webp","descScore":4.9,"serviceScore":4.9,"expressScore":3.9},"product_extra_infos":[{"product_id":"610000201303034940","field_name":"mrqseapu","field_value":"Kcyebkdm chtqyosuf uierjpdyt jfhjvovssj olxervwi bbkvtey gblqxh."}},"code":200,"message":"ok"}
 */
class Goods
{
    public function _empty($goods_id)
    {
        $goods_info = GoodsModel::get($goods_id);

        $goods_info['product_imgs'] = [
            [
                'id'    =>    time(),
                'product_id' => $goods_info['goods_id'],
                'url' => $goods_info['goods_cover']
            ]
        ];
        $goods_info['goods_return_g_price'] = round(($goods_info['goods_commission'] * config('return_g_price_rate') ) * config('return_g_exchange_rate'));
        $goods_info['goods_return_price'] = round( $goods_info['goods_commission'] * config('return_g_price_rate') ,2 );
        return ['data' => $goods_info , 'code' => 200, 'message' => 'ok'];
    }

    public function index()
    {
        //$page_size    =    $this->

        $max   = GoodsModel::count() - 30;
        $start = mt_rand(0 , $max);
        $goods = Goods::where('goods_ticket_left', '>' , 5000)->limit($start, 30)->order('goods_income_rate desc')->select();
        return json_output($goods);
    }
}

