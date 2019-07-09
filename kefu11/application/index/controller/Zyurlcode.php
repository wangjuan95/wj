<?php
/**
 * Zyurlcode by PhpStorm.
 * User: Dell
 * Date: 2019/6/13
 * Time: 10:40
 */
namespace app\index\controller;
use think\Controller;
use think\Db;

class Zyurlcode extends Controller
{

//圆通中通重量导出表
	public function zyUrl(){
		$res=Db::name('express_behavior')
			->where('request', 'neq','')
			->select();
		foreach($res as $key=>$v){
				if($v['title']=='中通调用寄件重量回调接口'){
					$data=[];
					$zhongtong=json_decode($v['request']);
					
					$data['waybill_number']=$zhongtong->billCode;
					if(isset($zhongtong->weight)){
                     $data['weight']=$zhongtong->weight;
					}
					if(isset($zhongtong->companyCode)){
					  $data['wuliucode']=$zhongtong->companyCode;
					}
					// dump($data);
                    
    				$res=Db::connect('db_con2')->name('weight')->insert($data);
	

				}else if($v['title']=='圆通调用寄件重量回调接口'){
					$data=[];
					$yuantong=urldecode($v['request']);
					//匹配物流单号
					preg_match_all('/<txLogisticID>*([\s\S]*?)<\/txLogisticID>/i',$yuantong,$state);
					if(isset($state[1][0])){
						$data['wuliucode']=$state[1][0];
					}
					//匹配运单号
                    preg_match_all('/<mailNo>*([\s\S]*?)<\/mailNo>/i',$yuantong,$bill);
					if(isset($bill[1][0])){
						$data['waybill_number']=$bill[1][0];
					}
					//匹配物流类型
                    preg_match_all('/<logisticProviderID>*([\s\S]*?)<\/logisticProviderID>/i',$yuantong,$type);
					if(isset($type[1][0])){
						$data['express_id']=$type[1][0];
					}
					//匹配时间
                    preg_match_all('/<acceptTime>*([\s\S]*?)<\/acceptTime>/i',$yuantong,$time);
					if(isset($time[1][0])){
						$data['accept_time']=$time[1][0];
					}

					//匹配重量
                    preg_match_all('/<weight>*([\s\S]*?)<\/weight>/i',$yuantong,$weight);
					if(isset($weight[1][0])){
						$data['weight']=$weight[1][0];
					}

					// dump($data);
					$res=Db::connect('db_con2')->name('weight')->insert($data);
					
				}
		}
	}


	//xml转数组

	public  function xmltoarr($xml){
		$objectxml = simplexml_load_string($xml);//将文件转换成对象
		$xmljson= json_encode($objectxml);//将对象转换个JSON
		$xmlarray=json_decode($xmljson,true);//将json转换成数组
		return $xmltoarr;

	}


}