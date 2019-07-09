<?php
/**
 * Mailing by PhpStorm.
 * User: Dell
 * Date: 2019/5/24
 * Time: 18:10
 */
namespace app\model;

use think\Model;
class Mailing extends Model{
//    protected $resultSetType = 'collection';
    //查询驿站寄快递订单
    public static function distributororder($id){
        $res=Mailing::where('distributor_id',$id)->where(['flag'=>1,'states'=>2])->order('create_time desc')->select();
        return $res;
    }
    //查询取消的订单
    public static function cancel(){
        $res=Mailing::where(['flag'=>2,'states'=>1])->order('create_time desc')->select()->toArray();
        return $res;
    }


}

