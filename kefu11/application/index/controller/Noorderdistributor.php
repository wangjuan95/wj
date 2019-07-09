<?php
/**
* Noorderdistributor.php by PhpStorm.
 * User: Dell
* Date: 2019/6/4
* Time: 15:40
*/
namespace app\index\controller;

use think\Controller;
use think\Db;


class Noorderdistributor extends Controller
{

//查询没有订单的驿站接口
      public function noOrder(){

          //查询驿站
          $ress=Db::table('pre_distributor')
                ->where('username is not null')
                ->order('signing_time asc')
                ->field('id,username,create_time,contact_number,signing_time')
                ->paginate(100);
          foreach ($ress as $k=>$v){
              $order=Db::table('pre_mailing')
                    ->where(['flag'=>1,'states'=>2])
                    ->where('distributor_id',$v['id'])
                    ->order('create_time desc')
                    ->find();
              if($order){

              }else{
                  $res[]=$v;
              }


          }
        //没有订单的驿站信息
        foreach($res as $key=>&$value){

            $rel=Db::table('pre_relation')->where('distributor_id',$value['id'])->find();

            $mark=Db::table('pre_market')->where('id',$rel['market_id'])->find();

            $value['market']=$mark['realname'];

            //开站距今已有多少天
            $timeday=time()-$value['signing_time'];
            $timeday= round($timeday / 86400);

//            echo '驿站名字：'.$value['username'].' **  区域经理：'.$value['market'].' ** 开站时间：'.date('Y-m-d',$value['signing_time']).'  **地址： '.$value['address'].' **手机号： '.$value['contact_number'].'   **距离现在有：'.$timeday.'天<br />';
            $value['time_day']=$timeday;
            // $arraynoorder[]=$value;
        }


          return json_encode([
              'code'=>200,
              'page'=>$ress,
              'data'=>$res]);






      }


}