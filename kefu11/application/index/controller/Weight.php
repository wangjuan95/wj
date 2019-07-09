<?php
/**
 * Weight by PhpStorm.
 * User: Dell
 * Date: 2019/6/12
 * Time: 10:40
 */
namespace app\index\controller;
use think\Controller;
use think\Db;
class Weight extends Controller
{
//物品重量寄件量
    public function weightCount(){
        if(request()->isPost()){
            //没有传参（日期）
            if(empty($_POST['time'])){
                $sql='select goods_weight from pre_mailing where flag=1 and states=2 order by goods_weight asc';
            }else{
            //有传参日期
                $time=$_POST['time'];
                $sql='select goods_weight from pre_mailing where flag=1 and states=2  and FROM_UNIXTIME(create_time, "%Y-%m-%d")="'.$time.'" order by goods_weight asc';


            }
            $res=Db::query($sql);
            if(empty($res)){
                return json_encode([
                    'code'=>101,
                    'msg'=>'没有当天数据',
                    'data'=>'',

                ]);
            }
            //把对应的数据数组放入对应的区间
            foreach($res as $key=>&$val){
                if((ceil($val['goods_weight'])- $val['goods_weight'])== 0){
                    if($val['goods_weight']==0){
                        $xiao=(int)$val['goods_weight'];
                        $da=(int)$val['goods_weight']+1;
                    }else{
                        $xiao=(int)$val['goods_weight']-1;
                        $da=(int)$val['goods_weight'];
                    }

                }else{
                   $da=(int)ceil($val['goods_weight']);
                   $xiao=(int)floor($val['goods_weight']);
                }
                $con[$xiao.'~'.$da][]=$val;
            }
            //求取数组长度，即寄件量
            if($con){
                foreach($con as $k=>$v){
                    $arr[$k]=sizeof($v);
                }
                if($arr){
                    return json_encode([
                        'code'=>200,
                        'msg'=>'返回成功',
                        'data'=>$arr,

                    ]);
                }
            }else{
                return json_encode([
                    'code'=>101,
                    'msg'=>'没有当天数据',
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


}