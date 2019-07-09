<?php
/**
 * Zycount by PhpStorm.
 * User: Dell
 * Date: 2019/6/13
 * Time: 10:40
 */
namespace app\index\controller;
use think\Controller;
use think\Db;

class Zycount extends Controller
{
	public function count(){
		$str='';
        $data=array_values($this->get_weeks());
        foreach ($data as $key=>$value){
            $str.='"'.$value.'",';
        }
        $strr=rtrim($str,',');
        $arr=array_reverse($data);
         $sql2= 'SELECT FROM_UNIXTIME(create_time, "%Y-%m-%d") as time, COUNT(*) AS count FROM pre_mailing WHERE flag=1 and states=2  and  FROM_UNIXTIME(create_time, "%Y-%m-%d")="'.$arr[0].'"';
         $today=Db::query($sql2);
         // dump($today);
        foreach($arr as $k=>$value){
        	// dump($k);
        	if($k<31){
        		 $sql= "SELECT FROM_UNIXTIME(create_time, '%Y-%m-%d') as time, COUNT(*) AS count FROM pre_mailing WHERE flag=1 and states=2  and  create_time BETWEEN ".strtotime($arr[$k+1])." AND ".strtotime($arr[$k])."  GROUP BY FROM_UNIXTIME(create_time, '%Y-%m-%d')";
	        	     $res[]=Db::query($sql);
	        	
        	}
        	
        }
        
        array_unshift($res,$today);
        // dump($res);
        if($res){
             return json_encode([
                    'code'=>200,
                    'msg'=>'返回成功',
                    'data'=>$res,

             ]);

        }  



	}




  //获取最近一个月所有日期

    function get_weeks($time = '', $format='Y-m-d'){
        $time = $time != '' ? $time : time();
        //组合数据
        $date = [];
        for ($i=1; $i<=32; $i++){
            $date[$i] = date($format ,strtotime( '+' . $i-32 .' days', $time));
        }
        return $date;
    }
}