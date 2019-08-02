<?php
namespace app\index\controller;
use think\Controller;
use think\View;
use think\Lang;
use app\common\model\Goods;
use app\common\model\Category as CategoryModel;
use think\Request;
use app\common\model\Brand;

class Category extends Controller
{
    public function index( Request $request )
    {
        $cat = $request->cat ? : 1;

        $brand = $request->brand ? : 1;

        $brand_banner = Brand::field('brand_cover_pc')->where('brand_id',$brand)->find();
        if ( $brand_banner ) {
            $banner = $brand_banner['brand_cover_pc'];
        }else{
            $cat_banner = CategoryModel::field('cat_banner')->where('cat_id' , $cat)->find();
            $banner = $cat_banner['cat_banner'];
        }

        $goods = Goods::where(['goods_cat'=>$cat])->order('goods_income_rate desc')->select();
        $this->assign('goods' , $goods);
        $this->assign('banner' , $banner);
        return $this->fetch();
    }


}
