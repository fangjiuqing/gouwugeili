<?php
namespace app\common\model;
use think\Model;

class Category extends Model {
    /**
     *
     */
    public function saveOrGetId($cat_name) {
        //运动/瑜伽/健身/球迷用品
        $obj = self::where('cat_name' , $cat_name)->findOrEmpty();
        if ( !$obj ) {
            $obj = self::create([
                'cat_name'   => $cat_name,
                'cat_level'  => 0,
                'cat_type'   => 1,
            ]);
        }

        return $obj->cat_id;
    }
}