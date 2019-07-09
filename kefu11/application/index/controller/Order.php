<?php
/**
 * Order by PhpStorm.
 * User: Dell
 * Date: 2019/6/10
 * Time: 18:10
 */
namespace app\index\controller;

use think\Controller;
use think\Db;


class Order extends Controller
{
   public  function water(){

       return view();
   }
    public  function laundry(){
       //订单
       $res=Db::table('pre_server_orders')->where('server_type',4)->select();
       foreach($res as $key=>$value){
           $did=$value['distributor_id'];
           //驿站
           $station=Db::table('pre_distributor')->where('id',$did)->find();
           $value['stationname']=$station['username'];
       }

        return view();
    }
    public  function repair(){
        return view();
    }

    //查询服务类型订单的接口

    public  function serverOrder(){

       if(request()->post('type')){

           $type=$_POST['type'];
           //订单
           $res=Db::table('pre_server_orders')->where('server_type',$type)->where('is_cancel',1)->select();
           foreach($res as $key=>$value){
               $did=$value['distributor_id'];
               //驿站
               $station=Db::table('pre_distributor')->where('id',$did)->find();
               $value['stationname']=$station['username'];
               $ress[]=$value;
           }

           return json_encode([
               'code'=>200,
               'msg'=>'true',
               'data'=>$ress]);

       }else{
           return json_encode([
               'code'=>100,
               'msg'=>'error',
               'data'=>'']);

       }


    }

    //搜索的订单
    public function serachOrder(){


        if(request()->post('name')){
             $name=$_POST['name'];
            //驿站
            $res=Db::table('pre_distributor')->where('username',$name)->select();
            if($res){
                foreach($res as $key=>&$value){
                    //驿站订单
                    $order=Db::table('pre_server_orders')->where('distributor_id',$value['id'])->where('server_type',4)->select();
                    foreach($order as $kk=>&$v){
                        if($value['id']==$v['distributor_id']){
                            $value[$value['id']][]=$v;
                        }else{

                        }

                    }
                    $resarr[]=$value;
                }

                return json_encode([
                    'code'=>200,
                    'msg'=>'返回成功',
                    'data'=>$resarr]);
            }else{
                return json_encode([
                    'code'=>101,
                    'msg'=>'输入驿站名字有误',
                    'data'=>'']);
            }


        }else{
            return json_encode([
                'code'=>100,
                'msg'=>'提交方式有误',
                'data'=>'']);

        }




        }




}