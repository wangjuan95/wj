<?php
/**
 * Back by PhpStorm.
 * User: Dell
 * Date: 2019/6/13
 * Time: 10:40
 */
namespace app\index\controller;
use think\Controller;
use think\Db;

class Back extends Controller
{
    public  function  back(){
        $distributor=Db::name('distributor')->field('id,username')->select();
        foreach($distributor as $key=>&$value){
            $relation=Db::name('relation')->where('distributor_id',$value['id'])->field('market_id')->find();
            $mark=Db::name('market')->where('id',$relation['market_id'])->field('realname')->find();
            $value['mark']=$mark['realname'];
            $order=Db::name('mailing')->where(['flag'=>1,'states'=>2])->where('distributor_id',$value['id'])->field('waybill_number,express_id,express_name,create_time,states,id')->select();
          if($order){
              foreach($order as $jian=>&$zhi){
                  $value['order'][$zhi['express_id']][]=$zhi;
              }
          }else{
              $value['order']=[];
          }

        }

        foreach($distributor as $kk=>$vv){
           foreach($vv['order'] as $k=>$ee ) {
               foreach($ee as $yy=>$v){

               //判断数据库中是否有数据
               $find = Db::connect('db_con2')->name('allorder')->where('oid', $v['id'])->find();
               if ($find) {

               } else {

//                   if ($v['express_id'] == '100101') {
//
//                       //授权id key
//                       $company_id = 'be0dbfb7f29742e99870ea449a79a55b';
//                       $key = '0dea3801685b';
//
//                       //调用地址
//                       $url = "http://japi.zto.cn/traceInterfaceNewTraces";
//
//                       //提交参数
//                       $fixedParams = Array();
//                       $ztlogin = $v["waybill_number"];
//                       $fixedParams["data"] = '["' . $ztlogin . '"]';
//
//                       //生成秘钥
//                       $fixedParams["company_id"] = 'be0dbfb7f29742e99870ea449a79a55b';
//                       $fixedParams["msg_type"] = 'NEW_TRACES';
//                       $str_to_digest = "";
//                       foreach ($fixedParams as $ke => $vvv) {
//                           $str_to_digest = $str_to_digest . $ke . "=" . $vvv . "&";
//                       }
//                       $str_to_digest = substr($str_to_digest, 0, -1) . $key;
//                       $data_digest = base64_encode(md5($str_to_digest, TRUE));
//
//                       //将信息添加到请求头
//                       $headers = Array(
//                           "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
//                           "x-companyid: " . $company_id,
//                           "x-datadigest: " . $data_digest
//                       );
//
//
//                       $fanhu = $this->postcurl($url, $headers, http_build_query($fixedParams), 2000);
//                       $result = json_decode($fanhu);
//
//                       if (empty($result->data[0]->traces)) {
//                           $state = 0;
//                       } else {
//                           $state = 1;
//                       }
//
//                   } else if ($v['express_id'] == '100102') {
//
//                       $yto_url = 'http://MarketingInterface.yto.net.cn';
//                       $data_transfer['app_key'] = 'aHgMEs';
//                       $data_transfer['format'] = 'XML';
//                       $data_transfer['method'] = 'yto.Marketing.WaybillTrace';
//                       $data_transfer['timestampdate'] = date('Y-m-d H:m:s', time());
//                       $data_transfer['user_id'] = 'bingbingdaojia';
//                       $data_transfer['v'] = '1.01';
//                       //生成签名
//                       $yto_sign = $this->ytojiami($data_transfer);
//
//                       $billcode = $v["waybill_number"];
//                       $yto_data_str['ufinterface']['Result']['WaybillCode']['Number'] = $billcode;
//
//                       $yto_param = $this->ytoarr2xml($yto_data_str, $root = true);
//
//                       //传递参数
//                       $yto_fixedParams = '';
//                       foreach ($data_transfer as $keyy => $valuee) {
//                           $yto_fixedParams .= $keyy . '=' . $valuee . '&';
//                       }
//
//                       //传递数据
//                       $yto_fixedParams = 'sign=' . $yto_sign . '&' . $yto_fixedParams . 'param=' . $yto_param;
//
//                       $yto_xml = $this->ytopostcurl($yto_url, $yto_fixedParams);
//
//                       $yto_data = $this->ytoxmlToArray($yto_xml);
//                       if (isset($yto_data['Result'])) {
//                           $yto_data = array_reverse($yto_data['Result']['WaybillProcessInfo']);
//                       }
//                       if (isset($yto_data[0])) {
//                           $state = 1;
//                       } else {
//                           $state = 0;
//                       }
//
//                   }

                   $count = $yy + 1;
                   $data = [
                       'oid' => $v['id'],
                       'waybill_number' => $v['waybill_number'],
                       'distributor' => $vv['username'],
                       'express_id' => $v['express_id'],
                       'express_name' => $v['express_name'],
                       'order_time' => $v['create_time'],
                       'area_manager' => $vv['mark'],
                       'distributor_id' => $vv['id'],
                       'states' => 0,
                       'create_time' => time(),
                       'sum_time' => $count,
                   ];


                   $up = Db::connect('db_con2')->name('allorder')->insert($data);
               }
           }

           }


        }





//        dump($distributor);
    }

//    function postcurl($url, $headers, $querystring, $timeout)
//    {
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);//设置链接
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);//设置HTTP头
//        curl_setopt($ch, CURLOPT_POST, 1);//设置为POST方式
//        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $querystring);
//        $response = curl_exec($ch);
//        return $response;
//
//    }
//
//    //数组  圆通加密
//    function ytojiami($data){
//        $fixedParams='';
//        foreach ($data as $key => $value) {
//            $fixedParams.=$key.$value;
//        }
//        $Secret_Key='sU9LTQ';
//        $str_to_digest = $Secret_Key.$fixedParams;
//        $data_digest =strtoupper(md5($str_to_digest));
//
//        return $data_digest;
//
//    }
//    function ytopostcurl($url,$querystring)
//    {
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);//设置链接
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
//        curl_setopt($ch, CURLOPT_POST, 1);//设置为POST方式
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $querystring);
//        $response = curl_exec($ch);
//        return $response;
//    }
//    function ytoxmlToArray($xml)
//    {
//        //禁止引用外部xml实体
//        libxml_disable_entity_loader(true);
//        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
//        return $values;
//    }
////数组转xml
//    function ytoarr2xml($data, $root = true)
//    {
//        $str = "";
/*        if ($root) $str .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>";*/
//        foreach ($data as $key => $val) {
//            if (is_array($val)) {
//                $child = $this->ytoarr2xml($val, false);
//                $str .= "<$key>$child</$key>";
//            } else {
//                $str .= "<$key>$val</$key>";
//            }
//        }
//        return $str;
//    }



}