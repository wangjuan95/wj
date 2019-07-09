<?php
/**
 * Dada by PhpStorm.
 * User: Dell
 * Date: 2019/6/27
 * Time: 10:40
 */
namespace app\index\controller;
use think\Controller;
use think\Db;

class Dada extends Controller
{
	//城市接口

	public function city(){

		//参数
		$app_key='dada79db27a3f3398e5';
		$body='';
		$format='json';
		$source_id='73753';
		$timestamp=time();
		$v='1.0';
		$app_secret='57bfd2facd147a7a9df29f28d2ff0a15';

        $url = "newopen.qa.imdada.cn/api/cityCode/list";
		//签名
		$signature1='app_key'.$app_key.'body'.$body.'formatjsonsource_id73753timestamp'.$timestamp.'v'.$v;

		$signature2=$app_secret.$signature1.$app_secret;

		$signature=strtoupper(MD5($signature2));
     
		$data=[
				'app_key'=>$app_key,
				'body'=>$body,
				'format'=>$format,
				'source_id'=>$source_id,
				'timestamp'=>$timestamp,
				'signature'=>$signature,
				'v'=>$v,
				'app_secret'=>$app_secret,
		];
       
        $arr=json_encode($data);

		$res=$this->postcurl($url,$arr);
		$res=json_decode($res);
		$city=$res->result;
		dump($city);
	    foreach($city as $key=>$v){
	    	$citydata['city']=$v->cityName;
            $citydata['code']=$v->cityCode;
            // $insert=Db::connect('db_con2')->name('city')->insert($citydata);
	    }



	}


	 public function postcurl($url,$data)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);//设置链接
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
        curl_setopt($ch, CURLOPT_POST, 1);//设置为POST方式
        curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        return $response;

    }

}