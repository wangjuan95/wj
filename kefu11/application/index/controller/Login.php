<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;

class Login extends Controller
{

    //登录
    public function  login(){

      if(request()->isPost()){
          $name=trim($_POST['name']);
          $pass=md5(trim($_POST['password']));
          $token=md5($name.$pass.time());
          if(empty($name)){
              return json_encode([
                  'code'=>100,
                  'msg'=>'输入参数为空',
                  'data'=>'']);
          }
          if(empty($pass)){
              return json_encode([
                  'code'=>100,
                  'msg'=>'输入参数为空',
                  'data'=>'']);
          }
          $res=Db::connect('db_con2')->table('pre_server_back')->where(['username'=>$name,'password'=>$pass])->find();
          if($res){

              Session::set('userlist',$res);
              return json_encode([
                  'code'=>200,
                  'msg'=>'返回成功',
                  'data'=>$res,
                  'token'=>$token,
                  ]);
          }else{

              return json_encode([
                  'code'=>101,
                  'msg'=>'输入的用户不存在',
                  'data'=>'']);
          }
      }else{
          return json_encode([
              'code'=>102,
              'msg'=>'不是post传参',
              'data'=>'']);
      }


    }


    //订单列表

    public function orderList(){
      if(isset($_POST['pagesize'])){

          $pagesize=intval(trim($_POST['pagesize']));

      }else{
          $pagesize=30;

      }
      if(isset($_POST['nu'])){

          $orderlist=Db::connect('db_con2')->table('pre_allorder')->order('order_time desc')->where('states',0)->paginate($pagesize);

      }else{
          $orderlist=Db::connect('db_con2')->table('pre_allorder')->order('order_time desc')->paginate($pagesize);

      }

      if($orderlist){
          return json_encode(['code'=>200,'msg'=>'success','data'=>$orderlist]);
      }else{
          return json_encode(['code'=>004,'msg'=>null,'data'=>'']);
      }
    }
	public function test()
	{
		echo 123;
	}




    public function wxsq(){
        $data = [];
        if(request()->isPost()){
            $code=$_POST['code'];
            $res = $this->get_info($code);

            $user = json_decode($res,true);
            $openid=$user['openid'];
            if(isset($user['errcode'])){
                return json(['data'=>$data,'msg'=>$user['errmsg'],'code'=>'fail']);
            }else{
                $seluser=Db::connect('db_con2')->table('pre_wx_shouquan')->where('openid',$openid)->find();
                if($seluser){
                    return json(['data'=>$seluser,'msg'=>'登录成功','code'=>'success']);
                }else{
                    $users['openid'] = $openid;
                    $users['create_time'] = time();
                    $insertuser=Db::connect('db_con2')->table('pre_wx_shouquan')->insert($users);
                    if($insertuser){
                        return json(['data'=>$users,'msg'=>'登录成功','code'=>'success']);
                    }else{
                        return json(['data'=>$data,'msg'=>'登录失败','code'=>'fail']);
                    }
                }

            }
        }


    }

    private function get_info($code){
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=wx2d67bd3568447340&secret=4bc95f1fcdf26458f28ac684f45a513c&js_code=".$code."&grant_type=authorization_code";
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        return $output;
    }









}
