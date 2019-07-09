<?php
/**
 * Distributorrec by PhpStorm.
 * User: Dell
 * Date: 2019/6/12
 * Time: 10:40
 */
namespace app\index\controller;
use app\model\Distributor;
use think\Controller;
use think\Db;

class Distributorrec extends Controller
{

    //驿站和快递员之间关联
    public function disrec(){
        $allres=Distributor::name('distributor')->order('create_time asc')->field('id,username')->paginate(6);
        $page = $allres->render();
        foreach($allres as $key=>&$value){
            $res=Db::connect('db_con2')->name('cou_rec')->group('receiverphone,recphone')->where('distributor_id',$value['id'])->field('id,receiver,receiverphone,recnetwork,recphone,express_id,waill_number,order_time,addr')->select();
            $value['recivermsg']=$res;
        }

        return view('',['res'=>$allres,'page'=>$page]);


    }


}