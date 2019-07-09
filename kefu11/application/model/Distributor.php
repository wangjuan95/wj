<?php
/**
* Distributor by PhpStorm.
* User: Dell
* Date: 2019/5/23
* Time: 18:10
*/
namespace app\model;

use think\Model;
class Distributor extends Model{

    //查询驿站根据手机号搜索
    public static function selectorder($phone){
        $res=Distributor::where('contact_number',$phone)->select();
        return $res;
    }


}