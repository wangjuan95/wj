<?php
/**
 * Repair by PhpStorm.
 * User: Dell
 * Date: 2019/6/13
 * Time: 10:40
 */
namespace app\index\controller;
use think\Controller;
use think\Db;

class Repair extends Controller
{
	public  function  repair(){

			if(empty($_GET['id'])){
					echo '参数id为空';
					 return json_encode([
			                    'code'=>101,
			                    'msg'=>'参数为空',
			                    'data'=>'',

			                ]);
				}
			
			if(!is_numeric($_GET['id'])){
						echo '输入ID格式有误';
					 return json_encode([
			                    'code'=>102,
			                    'msg'=>'请输入合格的id',
			                    'data'=>'',

			                ]);
				}	
			$id=$_GET['id'];
			$res=Db::name('trading_repair')->where('repair_id',108)->select();
			foreach($res as $key=>$v){
				$arr[]=$v['trading_name'];
			}
			array_unique($arr);
			// dump($arr);
			foreach($arr as $k=>$value){

				$res=Db::name('trading_repair')->where(['repair_id'=>$id,'trading_name'=>$value])->find();
				
				if($res){
                    echo $id.' 的 '.$value.' 已存在<br />';
				}else{

					$data=[
						'repair_id'=>$id,
						'trading_name'=>$value,
						'create_time'=>time()

					];
					// dump($data);die;
					$insert=Db::name('trading_repair')->insert($data);
					if($insert){
						echo $id.' 的 '.$value.' 操作成功<br />';
					}
						
					
				}


			}
			
	}



	//0-200循环调用
	public function rangeRepair(){
           $res=Db::name('trading_repair')->where('repair_id',108)->select();
			foreach($res as $key=>$v){
				$arr[]=$v['trading_name'];
			}
		    // dump($arr);die;
			array_unique($arr);
		for($id=1;$id<=200;$id++){
			
			foreach($arr as $k=>$value){

				$res=Db::name('trading_repair')->where(['repair_id'=>$id,'trading_name'=>$value])->find();
				
				if($res){
                  // echo $id;
				}else{

					$data=[
						'repair_id'=>$id,
						'trading_name'=>$value,
						'create_time'=>time()

					];
				
					$insert=Db::name('trading_repair')->insert($data);
					
						
					
				}


			}

		}





	}

	

}