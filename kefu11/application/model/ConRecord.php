<?php
/**
 * ConRecord by PhpStorm.
 * User: Dell
 * Date: 2019/5/24
 * Time: 18:10
 */
namespace app\model;

use think\Model;
class ConRecord extends Model{

    //查询驿站寄快递扣款明细
    public static function withholdcode($id){
        $res=ConRecord::where('distributor_id',$id)->where('title','余额充值')->order('create_time desc')->limit(30)->select();
        return $res;
    }

}

