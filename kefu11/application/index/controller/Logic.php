<?php
/**
 * Logic by PhpStorm.
 * User: Dell
 * Date: 2019/6/12
 * Time: 10:40
 */
namespace app\index\controller;
use think\Controller;
use think\Db;
class Logic extends Controller
{
    public function  logic(){
        if(request()->isPost()){

            if(empty($_POST['code'])||empty($_POST['waybill_number'])){
                return json_encode([
                    'code'=>101,
                    'msg'=>'提交参数有为空',
                    'data'=>'',

                ]);
            }
            $code=$_POST['code'];
            $waybill_number=$_POST['waybill_number'];
            if($code=='zhongtong'){
                //授权id key
                $company_id = 'be0dbfb7f29742e99870ea449a79a55b';
                $key = '0dea3801685b';

                //调用地址
                $url = "http://japi.zto.cn/traceInterfaceNewTraces";

                //提交参数
                $fixedParams = Array();
                $ztlogin=$waybill_number;
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
                $arr=$fanhu->data[0]->traces;
                $log=[];
                if(!empty($fanhu->data[0]->traces)){
                    foreach($arr as $ke=>$va){

                        $log[$ke]['time']=$va->scanDate;

                        $log[$ke]['des']=$va->desc;
                    }
                }else{
                    $log[]['des']='没有物流轨迹';
                }

                return json_encode([
                    'code'=>200,
                    'msg'=>'返回成功',
                    'data'=>$log,

                ]);


            }else if($code=='yuantong'){
                $yto_url='http://MarketingInterface.yto.net.cn';
                $data_transfer['app_key']='aHgMEs';
                $data_transfer['format']='XML';
                $data_transfer['method']='yto.Marketing.WaybillTrace';
                $data_transfer['timestampdate']=date('Y-m-d H:m:s',time());
                $data_transfer['user_id']='bingbingdaojia';
                $data_transfer['v']='1.01';
                //生成签名
                $yto_sign= $this->ytojiami($data_transfer);

                $billcode=$waybill_number;
                $yto_data_str['ufinterface']['Result']['WaybillCode']['Number']=$billcode;

                $yto_param=$this->ytoarr2xml($yto_data_str, $root=true);

                //传递参数
                $yto_fixedParams='';
                foreach ($data_transfer as $key => $value) {
                    $yto_fixedParams.=$key.'='.$value.'&';
                }

                //传递数据
                $yto_fixedParams='sign='.$yto_sign.'&'.$yto_fixedParams.'param='.$yto_param;

                $yto_xml=$this->ytopostcurl($yto_url,$yto_fixedParams);

                $yto_data=$this->ytoxmlToArray($yto_xml);
                if(isset($yto_data['Result'])){
                    $yto_data=array_reverse($yto_data['Result']['WaybillProcessInfo']);
                }

                if(isset($yto_data['reason'])){
                    $log[]['des']='没有物流轨迹';
                }else{
                    if(isset($yto_data[0])){
                        foreach($yto_data as $kk=>$vv){
                            $log[$kk]['time']=$vv['Upload_Time'];
                            $log[$kk]['des']=$vv['ProcessInfo'];
                        }
                    }else{
                        $log[0]['time']=$yto_data['Upload_Time'];
                        $log[0]['time']=$yto_data['ProcessInfo'];

                    }
                }
                return json_encode([
                    'code'=>200,
                    'msg'=>'返回成功',
                    'data'=>$log,

                ]);


            }else{
                return json_encode([
                    'code'=>101,
                    'msg'=>'公司编码有误',
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