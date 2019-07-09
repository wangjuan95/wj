<?php
/**
 * Mailingnodot by PhpStorm.
 * User: Dell
 * Date: 2019/6/13
 * Time: 10:40
 */
namespace app\index\controller;
use think\Controller;
use think\Db;

class Mailingnodot extends Controller
{

	//查询没有重量的订单
	public  function  noDotorder(){
		$nolist=Db::name('mailing')->where(['flag'=>1,'states'=>2])->where('dot_weight is null')->order('id','asc')->select();
		
		// dump($nolist);
		foreach($nolist as $key=>$v){
			$data['order_number']=$v['order_number'];
			$data['waybill_number']=$v['waybill_number'];
			$data['phone']=$v['send_phone'];
			// $res=Db::connect('db_con2')->table('pre_mailing_nodot')->insert($data);
		}
	}

	//根据订单号找重量
	public function orderToweight(){
		
		$nodot=Db::connect('db_con2')->table('pre_mailing_nodot')->where('states is null')->order('id','asc')->select();
		// print_r($nodot);
		foreach($nodot as $key=>$val){
			$arrlist=[];
			// dump($val['waybill_number']);
			$weight=Db::connect('db_con2')->name('weight')->where('express_id','YTO')->where('waybill_number',$val['waybill_number'])->find();
			if($weight){
				$arrlist['mailing']=$val['waybill_number'];
				$arrlist['nodot']=$weight['weight'];
				$arrlist['wuliucode']=$weight['wuliucode'];
				$allarr[$key]=$arrlist;
			    $allarr[$key]['id']=$val['id'];
			    $allarr[$key]['phone']=$val['phone'];
			    dump($arrlist['mailing']);
			    $res=$this->ytodiaozhong($allarr[$key]['mailing'],$allarr[$key]['nodot'],$allarr[$key]['wuliucode']);
			

			}
		   // sleep(1);
			
		}
		// dump($allarr);

		// foreach($allarr as $k=>$v){
			
			
		// }
	}


	//测试 圆通回调接口  
    public function ytodiaozhong($mailing,$nodot,$wuliucode){
           

              $yto_url="http://www.81dja.com/HzApier/ytopushWeight";
              //整合
              $yto_data['logisticProviderID']='YTO';
              $yto_data['clientID']='BBDJ';
              $yto_data['mailNo']=$mailing;
              $yto_data['txLogisticID']=$wuliucode;
              $yto_data['infoType']='STATUS';
              $yto_data['infoContent']='GOT';
              $yto_data['weight']=$nodot;
              $yto_data['acceptTime']=time();
              $yto_xml_data['UpdateInfo']=$yto_data;
              $yto_param=$this->ytoarr3xml($yto_xml_data, $root=true);
            
            
       
            //发送数据
            $partnerId='37PAZ18B';
            $logistics_interface=$yto_param;//数据xml
            $clientId='BBDJ';
            $type='offline';
            $digest_md5=md5($logistics_interface.$partnerId,true);
            // dump($digest_md5);die;
            // $data_getBytes=$this->ytogetBytes($digest_md5);
            // $data_ytotoStr=$this->ytotoStr($data_getBytes);
            $data_digest=base64_encode($digest_md5);
            
     


            $data_transfer['logistics_interface']=urlencode($logistics_interface);
            $data_transfer['data_digest']=urlencode($data_digest);
            $data_transfer['type']=$type;
            $data_transfer['clientId']=$clientId;
            $yto_fixedParams='';
            foreach ($data_transfer as $key => $value) {
              $yto_fixedParams.=$key.'='.$value.'&';
            }
            
          $yto_fixedParams=rtrim($yto_fixedParams, "&");
          // dump($yto_fixedParams);
          $yto_xml=$this->ytopostcurl($yto_url,$yto_fixedParams);  
          $yto_data=$this->ytoxmlToArray($yto_xml);
          // dump($yto_data);
          if($yto_data['success']=='true'){
				
					$upresult=Db::connect('db_con2')->name('mailing_nodot')
					->where([
						'order_number'=>$wuliucode,
						'waybill_number'=>$mailing
					])
					->update(['states'=>1]);
				}
				// dump($mailing);
				// dump($nodot);
				// dump($wuliucode);
			
        
             
            
        }

        public function ytopostcurl($url,$querystring)
		     {
		        $ch = curl_init();
		        curl_setopt($ch, CURLOPT_URL, $url);//设置链接
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
		        curl_setopt($ch, CURLOPT_POST, 1);//设置为POST方式
		        curl_setopt($ch, CURLOPT_POSTFIELDS, $querystring);
		        $response = curl_exec($ch);
		        return $response;
		    }
	   public  function ytoxmlToArray($xml)
	    {

	        //禁止引用外部xml实体
	        libxml_disable_entity_loader(true);
	        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
	        return $values;
	    }
	     //数组转xml
	    function ytoarr3xml($data, $root = true)
	    {
	        $str = "";
	        if ($root) $str .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
	        foreach ($data as $key => $val) {
	            if (is_array($val)) {
	                $child = $this->ytoarr3xml($val, false);
	                $str .= "<$key>$child</$key>";
	            } else {
	                $str .= "<$key>$val</$key>";
	            }
	        }
	        return $str;
	    }



}