<?php
namespace app\common\model;
use think\Model;

class Category extends Model {
    /**
     *
     */
    public static saveOrGetId($cat_name) {
        $res = self::where("cat_name = '{$cat_name}'")->find();
    }
}