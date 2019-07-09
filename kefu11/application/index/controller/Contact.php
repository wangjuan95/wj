<?php
/**
 * Contact by PhpStorm.
 * User: Dell
 * Date: 2019/6/13
 * Time: 10:40
 */
namespace app\index\controller;
use app\model\Mailing;
use think\Controller;
use think\Db;

class Contact extends Controller
{
    //查询收件员的信息
    public function selectReceive(){
        if(request()->isPost()){
            if(empty($_POST['id'])){
                return json_encode([
                    'code'=>101,
                    'msg'=>'参数为空',
                    'data'=>'',

                ]);
            }
            $distributor_id=$_POST['id'];
            $res=Db::connect('db_con2')->table('pre_cou_rec')->group('receiverphone,recphone')->where('distributor_id',$distributor_id)->select();
            if($res){
                foreach($res as $key=>$v){
                    $data[$key]['receiver']=$v['receiver'];
                    $data[$key]['receiverphone']=$v['receiverphone'];
                    $data[$key]['recnetwork']=$v['recnetwork'];
                    $data[$key]['recphone']=$v['recphone'];
//                    $data[$key]['express_id']=$v['express_id'];
//                    $data[$key]['waill_number']=$v['waill_number'];
//                    $data[$key]['order_time']=$v['order_time'];

                }
                return json_encode([
                    'code'=>200,
                    'msg'=>'返回成功',
                    'data'=>$data,

                ]);
            }else{
                return json_encode([
                    'code'=>202,
                    'msg'=>'没有数据',
                    'data'=>'',

                ]);
            }

        }else{
            return json_encode([
                'code'=>100,
                'msg'=>'提交方式错误',
                'data'=>'',

            ]);
        }

    }

    //派件员信息

    public function selectCourier(){
        if(request()->isPost()){
            if(empty($_POST['addr'])){
                return json_encode([
                    'code'=>101,
                    'msg'=>'参数为空',
                    'data'=>'',

                ]);
            }
            $addr=$_POST['addr'];
            $res=Db::connect('db_con2')->table('pre_cou_rec')->group('courierphone,couphone')->where('addr',$addr)->select();
            if($res){
                foreach($res as $key=>$v){
                    $data[$key]['courier']=$v['courier'];
                    $data[$key]['courierphone']=$v['courierphone'];
                    $data[$key]['counetwork']=$v['counetwork'];
                    $data[$key]['couphone']=$v['couphone'];
                    $data[$key]['express_id']=$v['express_id'];
                    $data[$key]['waill_number']=$v['waill_number'];
                    $data[$key]['order_time']=$v['order_time'];
                    $data[$key]['city']=$v['city'];
                    $data[$key]['area']=$v['area'];
                    $data[$key]['addr']=$v['addr'];
                }
                return json_encode([
                    'code'=>200,
                    'msg'=>'返回成功',
                    'data'=>$data,

                ]);

            }else{
                return json_encode([
                    'code'=>102,
                    'msg'=>'没有数据',
                    'data'=>'',

                ]);
            }


        }else{
            return json_encode([
                'code'=>100,
                'msg'=>'提交方式错误',
                'data'=>'',

            ]);
        }


    }


    //所有取消的订单
    public function cancelOrder(){

//            $cancel=Mailing::cancel();
            $cancel=Db::name('mailing')->where(['flag'=>2,'states'=>1])->order('create_time desc')->select();
            if($cancel){
                return json_encode([
                    'code'=>200,
                    'msg'=>'返回成功',
                    'data'=>$cancel,

                ]);
            }else{
                return json_encode([
                    'code'=>102,
                    'msg'=>'没有数据',
                    'data'=>'',

                ]);
            }


        }

    //根据日期找到当天的收件人的其他订单
    public function dayOtherorder(){
        if(request()->isPost()){
            if(empty($_POST['day'])){
                return json_encode([
                    'code'=>101,
                    'msg'=>'参数为空',
                    'data'=>'',

                ]);
            }
            $day=$_POST['day'];
            $is_date=strtotime($day)?strtotime($day):false;
            if($is_date===false){
                return json_encode([
                    'code'=>102,
                    'msg'=>'提交的日期格式不正确',
                    'data'=>'',

                ]);
            }
            $sql='select * from pre_mailing where flag=2 and states=1 and FROM_UNIXTIME(create_time, "%Y-%m-%d")="'.$day.'"';
            $res=Db::query($sql);
            if($res){
                foreach($res as $key=>$value){
                    $other=Db::name('mailing')->where(['flag'=>1,'states'=>2])->where(['collect_name'=>$value['collect_name'],'collect_phone'=>$value['collect_phone']])->select();
                    foreach($other as $k=>&$v){
                        $v['create_time']=date('Y/m/d H:i',$v['create_time']);
                    }


                    $data[$value['collect_phone']][$value['collect_name']]['otherorder']=$other;
                }
                return json_encode([
                    'code'=>200,
                    'msg'=>'返回成功',
                    'data'=>$data,

                ]);
            }else{
                return json_encode([
                    'code'=>200,
                    'msg'=>'没有数据',
                    'data'=>'',

                ]);
            }

        }else{
            return json_encode([
                'code'=>100,
                'msg'=>'提交方式错误',
                'data'=>'',

            ]);
        }

        //            $oneday=Db::table('pre_mailing')
//                ->where(['flag'=>2,'states'=>1])
//                ->where('DATE_FORMAT(create_time, "%Y-%m-%d")','=',$day)
//                ->select();
//            echo Db::getLastSql();
//            dump($res);

    }

    //查询所有的驿站
    public function yizhan(){
        $res=Db::name('distributor')->field('id,username')->select();
        return json_encode([
            'code'=>200,
            'msg'=>'返回成功',
            'data'=>$res,

        ]);
    }


}