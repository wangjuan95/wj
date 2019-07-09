<?php
/**
 * Courier by PhpStorm.
 * User: Dell
 * Date: 2019/6/12
 * Time: 10:40
 */
namespace app\index\controller;
use app\model\Mailing;
use think\Controller;
use think\Db;

class Courier extends Controller
{

    //根据驿站查询快递员
    public function distributordisCourier(){

        $order=Db::table('pre_mailing')->where(['flag'=>1,'states'=>2])->field('distributor_id,id,express_id,waybill_number,collect_city,collect_address,collect_area,create_time')->select();

        foreach($order as $key=>$value){
            $ss=Db::connect('db_con2')->table('pre_cou_rec')->where('oid',$value['id'])->field('courier,id,oid')->select();


            if($ss){

            }else{
                if($value['express_id']==100101){

                    //授权id key
                    $company_id = 'be0dbfb7f29742e99870ea449a79a55b';
                    $key = '0dea3801685b';

                    //调用地址
                    $url = "http://japi.zto.cn/traceInterfaceNewTraces";

                    //提交参数
                    $fixedParams = Array();
                    $ztlogin=$value['waybill_number'];
                    $fixedParams["data"]='["'.$ztlogin.'"]';

                    //生成秘钥
                    $fixedParams["company_id"]='be0dbfb7f29742e99870ea449a79a55b';
                    $fixedParams["msg_type"]='NEW_TRACES';
                    $str_to_digest = "";
                    foreach ($fixedParams as $k => $v) {
                        $str_to_digest = $str_to_digest.$k."=".$v."&";
                    }
                    $str_to_digest = substr($str_to_digest, 0, -1).$key;
                    $data_digest = base64_encode(md5($str_to_digest, TRUE));

                    //将信息添加到请求头
                    $headers = Array(
                        "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
                        "x-companyid: ".$company_id,
                        "x-datadigest: ".$data_digest
                    );

                    $fanhu= $this->postcurl($url, $headers, http_build_query($fixedParams), 2000);
                    $fanhu=json_decode($fanhu);

                    if(!empty($fanhu->data[0]->traces)){
                        $data=[];
                        $arr=$fanhu->data[0]->traces;
                        foreach($arr as $key=>$vv){

                            if($vv->scanType=='派件'){
                                $data['courier']=$vv->dispOrRecMan;
                                $data['courierphone']=$vv->dispOrRecManPhone;
                                $data['counetwork']=$vv->scanSite;
                                $data['couphone']=$vv->scanSitePhone;
                                $data['pai_time']=$vv->scanDate;

                            }
                            if($vv->scanType=='收件'){
                                $data['receiver']=$vv->dispOrRecMan;
                                $data['receiverphone']=$vv->dispOrRecManPhone;
                                $data['recnetwork']=$vv->scanSite;
                                $recdata=$vv->scanSitePhone;

                            }
                            $data['distributor_id']=$value['distributor_id'];
                            $data['waill_number']=$value['waybill_number'];
                            $data['express_id']=$value['express_id'];
                            $data['oid']=$value['id'];
                            $data['city']=$value['collect_city'];
                            $data['addr']=$value['collect_address'];
                            $data['area']=$value['collect_area'];
                            $data['order_time']=$value['create_time'];


                        }
                        if(isset($recdata)){
                            if(strpos($recdata,'、') ==false){
                                $data['recphone']=$recdata;
                                $res=Db::connect('db_con2')->table('pre_cou_rec')->insert($data);
                            }else{
                                $arrdata=explode('、',$recdata);
                                foreach($arrdata as $kk=>$val){
                                    $data['recphone']=$val;
                                    $res=Db::connect('db_con2')->table('pre_cou_rec')->insert($data);
                                }
                            }
                        }else{
                            $res=Db::connect('db_con2')->table('pre_cou_rec')->insert($data);
                        }


//
                    }

                }else if($value['express_id']==100102){
                    dump($value['waybill_number'].'中通');
                    $yto_url='http://MarketingInterface.yto.net.cn';
                    $data_transfer['app_key']='aHgMEs';
                    $data_transfer['format']='XML';
                    $data_transfer['method']='yto.Marketing.WaybillTrace';
                    $data_transfer['timestampdate']=date('Y-m-d H:m:s',time());
                    $data_transfer['user_id']='bingbingdaojia';
                    $data_transfer['v']='1.01';
                    //生成签名
                    $yto_sign= $this->ytojiami($data_transfer);

                    $billcode=$value['waybill_number'];
                    $yto_data_str['ufinterface']['Result']['WaybillCode']['Number']=$billcode;

                    $yto_param=$this->ytoarr2xml($yto_data_str, $root=true);

                    //传递参数
                    $yto_fixedParams='';
                    foreach ($data_transfer as $key => $valuee) {
                        $yto_fixedParams.=$key.'='.$valuee.'&';
                    }

                    //传递数据
                    $yto_fixedParams='sign='.$yto_sign.'&'.$yto_fixedParams.'param='.$yto_param;

                    $yto_xml=$this->ytopostcurl($yto_url,$yto_fixedParams);
                    $yto_data=$this->ytoxmlToArray($yto_xml);

                    if(isset($yto_data['Result'])){
                        $yto_data=array_reverse($yto_data['Result']['WaybillProcessInfo']);
                    }
                    if(isset($yto_data['reason'])){

                    }else{

                      $count=count($yto_data);
                      if(isset($yto_data[0])){
                      $data2=[];
                        foreach($yto_data as $v){

                                if(strstr($v['ProcessInfo'],'派件人')!=NULL){

                                    $str=$v['ProcessInfo'];
                                    $data2['pai_time']=$v['Upload_Time'];
                                    $arr=explode(' ',$str);
                                    if(isset($arr[2])){
                                        $data2['courier']=$arr[2];
                                    }else{
                                        $data2['courier']='';
                                    }
                                    if(isset($arr[4])) {
                                        $data2['courierphone'] = $arr[4];
                                    }else{
                                        $data2['courierphone'] ='';
                                    }
                                    if(isset($arr[0])) {
                                        $data2['counetwork'] = $arr[0];
                                    }else{
                                        $data2['counetwork'] = '';
                                    }

                                }
//
                            if(strstr($v['ProcessInfo'],'已收件')){
                                $strt=$yto_data[$count-1]["ProcessInfo"];
                                $shoujian=explode(' ',$strt);
                                $data2['recnetwork']=$shoujian[0];
                                if(isset($shoujian[2])) {
                                    $data2['receiver'] = $shoujian[2];
                                }else{
                                    $data2['receiver'] ='';
                                }
                            }


                            $data2['distributor_id']=$value['distributor_id'];
                            $data2['express_id']=$value['express_id'];
                            $data2['oid']=$value['id'];
                            $data2['receiverphone']='';
                            $data2['couphone']='';
                            $data2['recphone']='';
                            $data2['waill_number']=$value['waybill_number'];
                            $data2['city']=$value['collect_city'];
                            $data2['addr']=$value['collect_address'];
                            $data2['area']=$value['collect_area'];
                            $data2['order_time']=$value['create_time'];


                        }
                        $res=Db::connect('db_con2')->table('pre_cou_rec')->insert($data2);
                    }else{
                          $data3=[];
                        if(isset($yto_data['ProcessInfo'])){

                            if(strstr($yto_data['ProcessInfo'],'派件')!=NULL){
                                $str=$yto_data['ProcessInfo'];
                                $data3['pai_time']=$yto_data['Upload_Time'];
                                $arr=explode(' ',$str);
                                if(isset($arr[2])){
                                    $data3['courier']=$arr[2];
                                }else{
                                    $data3['courier']='';
                                }
                                if(isset($arr[4])) {
                                    $data3['courierphone'] = $arr[4];
                                }else{
                                    $data3['courierphone'] ='';
                                }
                                if(isset($arr[0])) {
                                    $data3['counetwork'] = $arr[0];
                                }else{
                                    $data3['counetwork'] = '';
                                }

                            }
                            if(strstr($yto_data['ProcessInfo'],'已收件')){
                                $shoujian=explode(' ',$yto_data['ProcessInfo']);
                                $data3['recnetwork']=$shoujian[0];
                                if(isset($shoujian[2])) {
                                    $data3['receiver'] = $shoujian[2];
                                }else{
                                    $data3['receiver'] = '';
                                }
                            }

                            $data3['distributor_id']=$value['distributor_id'];
                            $data3['express_id']=$value['express_id'];
                            $data3['oid']=$value['id'];
                            $data3['receiverphone']='';
                            $data3['couphone']='';
                            $data3['recphone']='';
                            $data3['waill_number']=$value['waybill_number']; $data3['city']=$value['collect_city'];
                            $data3['addr']=$value['collect_address'];
                            $data3['area']=$value['collect_area'];
                            $data3['order_time']=$value['create_time'];

                            $res=Db::connect('db_con2')->table('pre_cou_rec')->insert($data3);
                        }

                    }
                    }

                }
            }

        }


    }

    //curl  模拟post提交的处理机制

    public function postcurl($url, $headers, $querystring, $timeout)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);//设置链接
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);//设置HTTP头
        curl_setopt($ch, CURLOPT_POST, 1);//设置为POST方式
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $querystring);
        $response = curl_exec($ch);
        return $response;

    }


    //数组  圆通加密
    public function ytojiami($data){
        $fixedParams='';
        foreach ($data as $key => $value) {
            $fixedParams.=$key.$value;
        }
        $Secret_Key='sU9LTQ';
        $str_to_digest = $Secret_Key.$fixedParams;
        $data_digest =strtoupper(md5($str_to_digest));

        return $data_digest;

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
    function ytoxmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }
    //数组转xml
    function ytoarr2xml($data, $root = true)
    {
        $str = "";
        if ($root) $str .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                $child = $this->ytoarr2xml($val, false);
                $str .= "<$key>$child</$key>";
            } else {
                $str .= "<$key>$val</$key>";
            }
        }
        return $str;
    }

}