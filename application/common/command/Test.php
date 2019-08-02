<?php
namespace app\common\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use app\common\model\Goods;
use app\common\model\GoodsAlbum;
use app\common\model\Category;

use QL\QueryList;
use QL\Ext\PhantomJs;

// use TopClient;
// use TopClient\request\TbkItemGetRequest;

class Test extends Command
{
    protected function configure()
    {
        $this->setName('test')
            ->addArgument('name', Argument::OPTIONAL, "your name")
            ->addOption('city', null, Option::VALUE_REQUIRED, 'city name')
            ->setDescription('');
    }

    protected function execute(Input $input, Output $output)
    {
        $t1 = microtime(true);
        $m1 = memory_get_usage(true);
        echo sprintf('memory start used at:%.2fMb' , round( $m1 / 1024 / 1024 ,2 ) ) . PHP_EOL;
        $result = $this->confirm();

        if ( $result == 'N' ) {
            die();
        }

        $model       = new Goods();
        $album_model = new GoodsAlbum();

        $i = 0;
        $total = $model->count();
        echo '<pre>';

        ## 获取所有分类
        $cats = Category::field('cat_id,cat_name')->select();
        $update = [];
        while ( $i < ($total) ) {
            foreach ( $this->getOneData($model , $i) as $v ) {
                $update[$i] = [
                    'goods_id'    =>    $v->goods_id
                ];
                ## 到手价修改
                // if ( empty($v->goods_final_price) ) {
                //     preg_match('/满(\d+)元减(\d+)元/' , $v->goods_ticket_value , $match);

                //     if ( isset($match[1]) && isset($match[2]) ) {
                //         if ( $v->goods_origin_price >= $match[1] ) {
                //             $final_price    =    $v->goods_origin_price - $match[2];

                //             $update[$i]['goods_final_price']  = $final_price;
                //         }
                //     }

                //     preg_match('/(\d+)元无条件/' , $v->goods_ticket_value , $match);
                //     if ( isset($match[1]) ) {
                //         if ( $v->goods_origin_price >= $match[1] ) {
                //             $final_price    =    $v->goods_origin_price - $match[1];

                //             $update[$i]['goods_final_price']  = $final_price;
                //         }
                //     }
                // }


                # 获取评论数
                //$comment = $xpath->query('//*[@id="J_ItemRates"]/div/span[2]');

                # 获取详情
                // $nodeList  = $xpath->query('//*[@id="description"]/div');
                // foreach ($nodeList as $index => $node){
                //     echo $node->ownerDocument->saveHTML($node); //包含HTML标签
                // }


                ## 分类归集
                $cat_id = 0;
                foreach ( $cats as $cat ) {
                    if ( preg_match('/' . $cat['cat_name'] . '/' , $v->goods_full_cat . $v->goods_name ) ) {
                        $cat_id = $cat['cat_id'];
                        break;
                    }
                }

                $update[$i]['goods_cat']  = $cat_id;

                echo $v->goods_name ,' full cat :' , $v->goods_full_cat, ' cat:', $cat_id , PHP_EOL;
            }

            ## 间隔100次更新一下数据库
            // if ( ( ($i +1) % 100 ) == 0 ) {
            //     echo '批量更新，当前索引数：' . $i . PHP_EOL;
            //     if ( $model->saveAll($update) ) {
            //         echo '更新成功！' , PHP_EOL;
            //     }else{
            //         echo '更新失败' . PHP_EOL;
            //     }
            //     $update = [];
            // }

            $i++;
        }

        $t2 = microtime(true);
        echo '耗时'.round($t2-$t1,3).'秒' , PHP_EOL;
        $m2 = memory_get_usage(true);
        echo sprintf('memory end at:%.2fMb' , round( $m2 / 1024 / 1024 , 2) ) . PHP_EOL;
        echo sprintf('memory used about:%.2fMb' , round( ($m2 - $m1) / 1024 / 1024 , 2 ) ) . PHP_EOL;
    }

    protected function getOneData ($model , $start = 0 )
    {
        $obj = $model::limit($start,1)->order('goods_create_time desc')->select();
        if ( !$obj ) {
            yield [];
        }
        yield $obj[0];
    }

    protected function getCatId( $cats = [] )
    {
        if ( empty($cats) ) return 0;

        $nums = count($cats);
        $cat_id = 0;
        foreach ( $cats as $k => $cat ) {
            $cid = Category::saveOrGetId($cat);

            ## 返回最后一个分类ID
            if ( $k == $nums ) {
                $cat_id = $cid;
            }
        }
        return $cat_id;
    }

    /***
     * 终端给提示获取用户数据
     */
    protected function confirm($str = '继续执行')
    {
        //提示输入
        fwrite(STDOUT, $str . "(Y/N):");
        //获取用户输入数据
        $result = trim(fgets(STDIN));
        return trim($result);
    }

    protected function testLoadDynamicPage()
    {
        $ql = QueryList::getInstance();
        // 安装时需要设置PhantomJS二进制文件路径
        $bin_path = '/Users/l/Downloads/phantomjs-2.1.1-macosx/bin/phantomjs';

        $ql->use(PhantomJs::class,$bin_path);
        //or Custom function name
        $ql->use(PhantomJs::class,$bin_path,'browser');


        $data = $ql->browser('https://detail.tmall.com/item.htm?id=555252196942')->find('.content')->texts();
        print_r($data->all());
    }
}