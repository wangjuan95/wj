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

class Twocode extends Controller
{
	//获取access_token
	public function accessToken(){
        $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx1ac97ede09d653da&secret=ae64df4cf2bcfd7831e58ce9c27efb6e';

        $content=file_get_contents($url);
        $access=json_decode($content);
  
        $access_token=$access->access_token;
        return $access_token;

	}

	//获取二维码图片

	public function  twoCodeimg(){
		for($i=1;$i<=1000;$i++){

			$phone='0';

			if($i>=1 && $i<=9){

				$number='000'.$i;

			}else if($i>=10 && $i<=99){

				$number='00'.$i;

			}else if($i>=100 && $i<=999){

				$number='0'.$i;

			}else{

				$number=$i;

			}

			$scene='phone='.$phone.'&'.'number='.$number;
			// $scene='{"phone":'.$phone.','.'"number":'.$number.'}';

		    $qr_path = "./Uploads/";

            if(!file_exists($qr_path.'user/')){

                mkdir($qr_path.'user/', 0700,true);//判断保存目录是否存在，不存在自动生成文件目录
            }

            $filename = 'user/'.'A'.$number.'.png';

            $file = $qr_path.$filename;				

 			$token=$this->accessToken();

            $url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$token;

            $qrcode = array(
                'scene'			=> $scene,//二维码所带参数
                // 'width'			=> 200,
                // 'page'			=> '',//二维码跳转路径（要已发布小程序）
                // 'auto_color'	=> true
            );

            $result = $this->postcurl($url,json_encode($qrcode));//请求微信接口

            $errcode = json_decode($result,true)['errcode'];
            $errmsg = json_decode($result,true)['errmsg'];
            if($errcode) {
                $this->render(0,$errmsg);
            }

            $res = file_put_contents($file,$result);//将微信返回的图片数据流写入文件

            // if($res===false){
            //     $this->render(0,'生成二维码失败');
            // }else{
            //$this->return['data'] = $this->config->item('base_url').'/Uploads/'.$filename;
            //返回图片地址链接给前端
            // }
        }

	}

	 public function postcurl($url,$data)
	    {
	    $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检测
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:')); //解决数据包大不能提交
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_HTTPHEADER,array('Content-type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回

        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);
        }
        curl_close($curl); // 关键CURL会话
        return $tmpInfo; // 返回数据

	    }



}