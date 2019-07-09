<?php
namespace app\index\controller;

use think\Controller;
use think\Db;


class Logistics extends Controller
{

//调用中通接口
    /*
       * @ztlogin 快递单号
       */
    public function allotZhongtong(){

        //授权id key
        $company_id = 'be0dbfb7f29742e99870ea449a79a55b';
        $key = '0dea3801685b';

        //调用地址
        $url = "http://japi.zto.cn/traceInterfaceNewTraces";

        //提交参数
        $fixedParams = Array();
        $ztlogin=$_POST['ztlogin'];
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
        echo $fanhu;
        $result=json_decode($fanhu);

        $msg=$result->msg;
        $status=$result->status;

        if(empty($result->data[0]->traces)){
            $state=0;
        }else{
            $state=1;
        }

        $arr=[
            'billcode'=>$ztlogin,
            'traces'=>serialize($result->data),
            'msg'=>$msg,
            'status'=>$status,
            'state'=>$state,
            'create_time'=>time(),
        ];

        $res=Db::connect('db_con2')->table('pre_logistics_ztyt')->insert($arr);


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


//调用圆通接口
    public  function allotYuantong(){

        $yto_url='http://MarketingInterface.yto.net.cn';
        $data_transfer['app_key']='aHgMEs';
        $data_transfer['format']='XML';
        $data_transfer['method']='yto.Marketing.WaybillTrace';
        $data_transfer['timestampdate']=date('Y-m-d H:m:s',time());
        $data_transfer['user_id']='bingbingdaojia';
        $data_transfer['v']='1.01';
        //生成签名
        $yto_sign= $this->ytojiami($data_transfer);

        $billcode=$_POST['Number'];
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
       if(isset($yto_data[0])){
           $state=1;
       }else{
           $state=0;
       }
       dump($yto_data);

        $arr=[
            'billcode'=>$billcode,
            'traces'=>serialize($yto_data),
            'state'=>$state,
            'create_time'=>time(),
        ];

        $res=Db::connect('db_con2')->table('pre_logistics_ztyt')->insert($arr);


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


    //根据快递公司运单号查询物流   第三方限制每天100条
    /*
     * @com 快递公司编号
     * @num 快递单号
     */
    public function logisticsQuery(){

        //参数设置
        $key = 'nYgfyeCm9865';						//客户授权key
        $customer = '5868005F5F7A95B26730E93C51AF0AE0';					//查询公司编号
        $param = array (
            'com' => $_POST['com'],			//快递公司编码
            'num' => $_POST['num'],	//快递单号
            'phone' =>'',				//手机号
            'from' => '',				//出发地城市
            'to' =>'',					//目的地城市
            'resultv2' => '1'			//开启行政区域解析
        );

//请求参数
        $post_data = array();
        $post_data["customer"] = $customer;
        $post_data["param"] = json_encode($param);
        $sign = md5($post_data["param"].$key.$post_data["customer"]);
        $post_data["sign"] = strtoupper($sign);

        $url = 'http://poll.kuaidi100.com/poll/query.do';	//实时查询请求地址

        $params = "";
        foreach ($post_data as $k=>$v) {
            $params .= "$k=".urlencode($v)."&";		//默认UTF-8编码格式
        }
        $post_data = substr($params, 0, -1);


//发送post请求
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);

        $jsondata = str_replace("\"", '"', $result );


        echo $jsondata;

    }







}