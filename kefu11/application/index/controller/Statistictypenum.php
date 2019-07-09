<?php
/**
 * Statistictypenum by PhpStorm.
 * User: Dell
 * Date: 2019/6/12
 * Time: 10:40
 */
namespace app\index\controller;
use think\Controller;
use think\Db;

class Statistictypenum extends Controller
{
    //查询近一个月的平台计件量
    public function monthnum(){
        $data=array_reverse(array_values($this->get_weeks()));
        foreach($data as $k=>&$v){
            //$i=1 公众号  $i=2 app $i=3 小程序
            for($i=1;$i<=3;$i++){
                $sql='select count(types) as num from pre_mailing where flag=1 and states=2 and types='.$i.' and FROM_UNIXTIME(create_time, "%Y-%m-%d")="'.$v.'"';
                $res[$v][]=Db::query($sql);
            }

        }
        if($res){
            return json_encode([
                'code'=>200,
                'msg'=>'返回成功 顺序是公众号、app、小程序',
                'data'=>$res,

            ]);
        }else{
            return json_encode([
                'code'=>200,
                'msg'=>'没有数据',
                'data'=>'',

            ]);
        }


    }
    //获取最近一个月的日期

    function get_weeks($time = '', $format='Y-m-d'){
        $time = $time != '' ? $time : time();
        //组合数据
        $date = [];
        for ($i=1; $i<=31; $i++){
            $date[$i] = date($format ,strtotime( '+' . $i-31 .' days', $time));
        }
        return $date;
    }

    //统计每天发快递的数量
    public function  dayTypenum(){
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
            //$i=1 公众号  $i=2 app $i=3 小程序
            for($i=1;$i<=3;$i++){
                $sql='select count(types) as num from pre_mailing where flag=1 and states=2 and types='.$i.' and FROM_UNIXTIME(create_time, "%Y-%m-%d")="'.$day.'"';
                $res[]=Db::query($sql);
            }

            if($res){
                return json_encode([
                    'code'=>200,
                    'msg'=>'返回成功 顺序是公众号、app、小程序',
                    'data'=>$res,

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

    }



}