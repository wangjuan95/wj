<?php
/**
 * Distributor by PhpStorm.
 * User: Dell
 * Date: 2019/5/23
 * Time: 18:10
 */
namespace app\index\controller;

use app\model\ConRecord;
use app\model\Mailing;
use think\Session;
use think\Controller;
use app\model\Distributor as Yizhan;
use think\Db;

class Index extends Controller
{

    public function top(){
        return vier();
    }
    public  function left(){
        return view();
    }
    //登录
    public function  login(){

        if(request()->isPost()){
            $name=trim($_POST['name']);
            $pass=md5(trim($_POST['password']));

            if(empty($name)||empty($pass)){
                return '<script>window.location.href="http://kefu.81bb.cn/index.php/index/index/login";</script>';
            }

            $res=Db::connect('db_con2')->table('pre_kefu_admin')->where(['username'=>$name,'password'=>$pass])->find();
            if($res){
                Session::set('userlist',$res);
                $this->redirect('/');

            }else{
                return '<script>window.location.href="http://kefu.81bb.cn/index.php/index/index/login";</script>';
            }
        }else{
            return view();
        }



    }

    //退出
    public  function  out(){
        //销毁session
        session("userlist", NULL);
        //跳转页面
        $this->redirect('./index.php/index/index/login');

    }


    //登录成功首页
    public function index()
    {
        if(Session::get('userlist')==null){
            return '<script> window.location.href="http://kefu.81bb.cn/index.php/index/index/login"; </script> ';
        }

                if(isset($_POST['phone'])){
                    //表单提交数据
                    $phone=$_POST['phone'];
                    $res=Yizhan::selectorder($phone);

                    if($res){

                       //查询驿站id
                        $distributorid=$res[0]['id'];

                        //查询区域经理
                        $mark=Db::name('relation')->where('distributor_id',$distributorid)->find();

                        $market=Db::name('market')->where('id',$mark['market_id'])->find();

                        //查询寄件订单
                        $order=Mailing::distributororder($distributorid);
                        if($order){

                            //查询驿站短信充值明细
                            $withhold=ConRecord::withholdcode($distributorid);

                            return view('',['res'=>$res,'order'=>$order,'withhold'=>$withhold,'market'=>$market]);
                        }
                        return view('',['res'=>$res,'market'=>$market]);
                    }else{
                        return view('');
                    }

                }else{

                    return view();
                }

//        }
    }

}
