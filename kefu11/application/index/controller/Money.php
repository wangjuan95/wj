<?php
/**
 * Money by PhpStorm.
 * User: Dell
 * Date: 2019/6/13
 * Time: 10:40
 */
namespace app\index\controller;
use think\Controller;
use think\Db;

class Money extends Controller
{

	//导出表
	public function index(){
		return view();
	}

	//导出驿站表
	public function distributor(){
		$distributor=Db::name('distributor')->order('id asc')->select();
		// dump($distributor);

        foreach ($distributor as $key => $value) {
           
           $data[$key]['id']=$value['id'];
           $data[$key]['account']=$value['account'];
           $data[$key]['password']=$value['password'];
           $data[$key]['headimg']=$value['headimg'];
           $data[$key]['username']=$value['username'];
           $data[$key]['realname']=$value['realname'];
           $data[$key]['idcard']=$value['idcard'];
           $data[$key]['license']=$value['license'];
           $data[$key]['contacts']=$value['contacts'];
           $data[$key]['contact_number']=$value['contact_number'];
           $data[$key]['contact_email']=$value['contact_email'];
           $data[$key]['balance']=$value['balance'];
           $data[$key]['min_balance']=$value['min_balance'];
           $data[$key]['identity']=$value['identity'];
           $data[$key]['message']=$value['message'];
           $data[$key]['face_sheet']=$value['face_sheet'];
           $data[$key]['signing_time']=date('Y-m-d H:i:s',$value['signing_time']);
           $data[$key]['last_login_time']=date('Y-m-d H:i:s',$value['last_login_time']);
           $data[$key]['flag']=$value['flag'];
           $data[$key]['prohibit']=$value['prohibit'];
           $data[$key]['station_number']=$value['station_number'];
           $data[$key]['number']=$value['number'];
           $data[$key]['create_time']=date('Y-m-d H:i:s',$value['create_time']);
           $data[$key]['region']=$value['region'];
           $data[$key]['address']=$value['address'];
           $data[$key]['integral']=$value['integral'];
                 
        }

        $field = array(
            'A' => array('id', '驿站id'),
            'B' => array('account', '登录账号'),
            'C' => array('password', '密码'),
            'D' => array('headimg', '头像'),
            'E' => array('username', '驿站名称'),
            'F' => array('realname', '姓名'),
            'G' => array('idcard', '身份证'), 
            'H' => array('license', '营业执照'),
            'I' => array('contacts', '联系人'),
            'J' => array('contact_number', '联系人电话'),
            'K' => array('contact_email', '联系人邮件'),
            'L' => array('balance', '账户余额'),
            'M' => array('min_balance', '最低余额'),
            'N' => array('identity', '用户身份（1：社区，2：商圈）'),
            'O' => array('message', '短信余额'),
            'P' => array('face_sheet', '面单余额'),
            'Q' => array('signing_time', '签约时间'),
            'R' => array('last_login_time', '最后登录日期'),
            'S' => array('flag', '审核'),
            'T' => array('prohibit', '禁止登录'),
            'U' => array('station_number', '驿站编号'),  
            'V' => array('number', '驿站编号序号'),
            'W' => array('create_time', '创建时间'),
            'X' => array('region', '地区'),
            'Y' => array('address', '详细地址'),
            'Z' => array('integral', '积分'),


        );

        $this->phpExcelList($field, $data, '驿站数据_' . date('Y-m-d'));

	}

	//消费记录表
	public function record(){
		$recordlist=Db::name('con_record')->order('id asc')->select();
		
		foreach($recordlist as $key=>$value){

			 $data[$key]['id']=$value['id'];
			 $data[$key]['distributor_id']=$value['distributor_id'];
			 $data[$key]['title']=$value['title'];
			 $data[$key]['con_amount']=$value['con_amount'];
			 $data[$key]['con_balance']=$value['con_balance'];
			 $data[$key]['budget']=$value['budget'];
			 $data[$key]['types']=$value['types'];
        $data[$key]['order_number']=$value['order_number'];
			 $data[$key]['create_time']=date('Y-m-d H:i:s',$value['create_time']);

		}
		 $field = array(
            'A' => array('id', 'id'),
            'B' => array('distributor_id', '驿站id'),
            'C' => array('title', '描述'),
            'D' => array('con_amount', '消费金额'),
            'E' => array('con_balance', '消费余额'),
            'F' => array('budget', '收支1支出2收入'),
            'G' => array('types', '类型1发货费2服务费3物料费'),
            'H' => array('order_number', '编号'),
            'I' => array('create_time', '添加时间'),
      
        );

        $this->phpExcelList($field, $data, '驿站消费记录数据_' . date('Y-m-d'));


	}

	//短信充值记录表

	public function message(){
		$message=Db::name('message_record')->order('id asc')->select();
		// dump($message);
		foreach($message as $key=>$value){
             $data[$key]['id']=$value['id'];
			 $data[$key]['distributor_id']=$value['distributor_id'];
			 $data[$key]['order_number']=$value['order_number'];
			 $data[$key]['money']=$value['money'];
			 $data[$key]['number']=$value['number'];
			 $data[$key]['states']=$value['states'];
			 $data[$key]['create_time']=date('Y-m-d H:i:s',$value['create_time']);
		}
		 $field = array(
            'A' => array('id', 'id'),
            'B' => array('distributor_id', '驿站id'),
            'C' => array('order_number', '订单号'),
            'D' => array('money', '金额'),
            'E' => array('number', '条数'),
            'F' => array('states', '是否充值成功1是2否'),
            'G' => array('create_time', '添加时间'),
      
        );

        $this->phpExcelList($field, $data, '短信充值记录数据_' . date('Y-m-d'));


	}


	//面单充值记录
	public  function  facesheet(){

		$message=Db::name('facesheet_record')->order('id asc')->select();
		// dump($message);
		foreach($message as $key=>$value){
       $data[$key]['id']=$value['id'];
			 $data[$key]['distributor_id']=$value['distributor_id'];
			 $data[$key]['order_number']=$value['order_number'];
			 $data[$key]['money']=$value['money'];
			 $data[$key]['number']=$value['number'];
			 $data[$key]['states']=$value['states'];
			 $data[$key]['create_time']=date('Y-m-d H:i:s',$value['create_time']);
		}
		 $field = array(
            'A' => array('id', 'id'),
            'B' => array('distributor_id', '驿站id'),
            'C' => array('order_number', '订单号'),
            'D' => array('money', '金额'),
            'E' => array('number', '条数'),
            'F' => array('states', '是否充值成功1是2否'),
            'G' => array('create_time', '添加时间'),
      
        );

        $this->phpExcelList($field, $data, '面单充值记录数据_' . date('Y-m-d'));
	}

  //驿站关联运单号
  public function disrecord(){
    $code=$_POST['dis_id'];
    if(empty($code)){
      return false;
    }
    $dis=Db::name('distributor')->where('station_number',$code)->field('id,username,station_number')->find();
    $record=Db::name('con_record')->where('con_amount','<>',0.0)->where('order_number is not null')->where('distributor_id',$dis['id'])->order('id desc')->field('distributor_id,title,con_amount,con_balance,budget,types,order_number')->select();
  
    foreach($record as $k=>$val){
        $mailing=Db::name('mailing')->where('id',$val['order_number'])->where(['flag'=>1,'states'=>2])->field('id,waybill_number,collect_name,collect_phone,collect_region,collect_address,create_time')->find();
        if($mailing){
          $arr[]=array_merge($val,$mailing); 
        }
        
    }
   

    foreach($arr as $key=>$value){
       $data[$key]['username']=$dis['username'];
       $data[$key]['distributor_id']=$dis['station_number'];
       $data[$key]['title']=$value['title'];
       $data[$key]['con_amount']=$value['con_amount'];
       $data[$key]['con_balance']=$value['con_balance'];
       $data[$key]['budget']=$value['budget']==1 ? '+' : '-';
       $data[$key]['types']=$value['types'];
       $data[$key]['waybill_number']=$value['waybill_number'];
       $data[$key]['collect_region']=$value['collect_region'];
       $data[$key]['collect_name']=$value['collect_name'];
       $data[$key]['collect_phone']=$value['collect_phone'];
       $data[$key]['collect_address']=$value['collect_address'];
       
       
       $data[$key]['create_time']=date('Y-m-d H:i:s',$value['create_time']);
    }
     $field = array(
            'A' => array('username', '驿站名字'),
            'B' => array('distributor_id', '驿站编号'),
            'C' => array('title', '描述'),
            'D' => array('con_amount', '消费金额'),
            'E' => array('con_balance', '消费余额'),
            'F' => array('budget', '收支'),
            'G' => array('types', '类型1发货费2服务费3物料费5生活服务'),
            'H' => array('waybill_number', '运单号'),
             
            'I' => array('collect_name', '收件人姓名'),
            'J' => array('collect_phone', '收件人电话'),
            'K' => array('collect_region', '地址'),
            'L' => array('collect_address', '详细地址'),
            'M' => array('create_time', '时间'),
      
        );

        $this->phpExcelList($field, $data, '驿站消费记录关联运单号收件地址表_' . date('Y-m-d'));





  }

  //驿站关联订单
  public function dismiling(){

     $code=$_POST['distributor'];
    if(empty($code)){
      return false;
    }

    $dis=Db::name('distributor')->where('station_number',$code)->field('id,username,station_number')->find();

    $mailing=Db::name('mailing')->where('dot_money','<>',0.0)->where('distributor_id',$dis['id'])->where(['flag'=>1,'states'=>2])->field('id,waybill_number,dot_money,collect_name,collect_phone,collect_region,collect_address,create_time')->order('id desc')->select();

    foreach($mailing as $key=>$value){
       $data[$key]['username']=$dis['username'];
       $data[$key]['distributor_id']=$dis['station_number'];
       $data[$key]['dot_money']=$value['dot_money'];
       $data[$key]['waybill_number']=$value['waybill_number'];
       $data[$key]['collect_region']=$value['collect_region'];
       $data[$key]['collect_name']=$value['collect_name'];
       $data[$key]['collect_phone']=$value['collect_phone'];
       $data[$key]['collect_address']=$value['collect_address']; 
       $data[$key]['create_time']=date('Y-m-d H:i:s',$value['create_time']);
    }
     $field = array(
            'A' => array('username', '驿站名字'),
            'B' => array('distributor_id', '驿站编号'),
            'C' => array('dot_money', '网点金额'),
            'D' => array('waybill_number', '运单号'),
             
            'E' => array('collect_name', '收件人姓名'),
            'F' => array('collect_phone', '收件人电话'),
            'G' => array('collect_region', '地址'),
            'H' => array('collect_address', '详细地址'),
            'I' => array('create_time', '时间'),
      
        );

        $this->phpExcelList($field, $data, '驿站关联订单收件地址表_' . date('Y-m-d'));




  }






	 public function phpExcelList($field, $list, $title='文件')
    {
        vendor('PHPExcel.PHPExcel');
        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel); //设置保存版本格式
        foreach ($list as $key => $value) {
            foreach ($field as $k => $v) {
                if ($key == 0) {
                    $objPHPExcel->getActiveSheet()->setCellValue($k . '1', $v[1]);
                }
                $i = $key + 2; //表格是从2开始的
                $objPHPExcel->getActiveSheet()->setCellValue($k . $i, $value[$v[0]]);
            }

        }
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename='.$title.'.xls');
        header("Content-Transfer-Encoding:binary");
//        $objWriter->save($title.'.xls');
        $objWriter->save('php://output');
    }


    //驿站订单
    public function disorder(){
       $distributor=Db::name('distributor')->field('id,username,station_number')->order('id asc')->select();
       //dump($distributor);
       foreach($distributor as $ke=>$val){
            $order=Db::name('mailing')->where(['flag'=>1,'states'=>2,'distributor_id'=>$val['id']])->field('distributor_id,waybill_number,dot_money,express_name,send_name,send_phone,send_region,send_address,collect_name,collect_phone,collect_region,collect_address,create_time')->select();
           if($order){
              foreach($order as $k=>$v){
                $arrlist[$val['username']][$k]['id']=$val['id'];
                $arrlist[$val['username']][$k]['username']=$val['username'];
                $arrlist[$val['username']][$k]['station_number']=$val['station_number'];
                $arrlist[$val['username']][$k]['waybill_number']=$v['waybill_number'];
                $arrlist[$val['username']][$k]['dot_money']=$v['dot_money'];
                $arrlist[$val['username']][$k]['express_name']=$v['express_name'];
                $arrlist[$val['username']][$k]['send_name']=$v['send_name'];
                $arrlist[$val['username']][$k]['send_phone']=$v['send_phone'];
                $arrlist[$val['username']][$k]['send_region']=$v['send_region'];
                $arrlist[$val['username']][$k]['send_address']=$v['send_address'];
                $arrlist[$val['username']][$k]['collect_name']=$v['collect_name'];
                $arrlist[$val['username']][$k]['collect_phone']=$v['collect_phone'];
                $arrlist[$val['username']][$k]['collect_region']=$v['collect_region'];
                $arrlist[$val['username']][$k]['collect_address']=$v['collect_address'];
                $arrlist[$val['username']][$k]['create_time']=$v['create_time'];
              }
              
              
           }

       }
        foreach($arrlist as $arr=>$list){
          foreach($list as $a=>$n){
            $arrorder[]=$n;
          }
          
        }
       // dump($arrorder);die;
      
         foreach($arrorder as $key=>$value){
                   $data[$key]['username']=$value['username'];
                   $data[$key]['distributor_id']=$value['station_number'];
                   $data[$key]['dot_money']=$value['dot_money'];
                   $data[$key]['waybill_number']=$value['waybill_number'];
                    $data[$key]['express_name']=$value['express_name'];
                   $data[$key]['send_name']=$value['send_name'];
                   $data[$key]['send_phone']=$value['send_phone'];
                   $data[$key]['send_region']=$value['send_region'];
                   $data[$key]['send_address']=$value['send_address']; 
                   $data[$key]['collect_name']=$value['collect_name'];
                   $data[$key]['collect_phone']=$value['collect_phone'];
                   $data[$key]['collect_region']=$value['collect_region'];
                   
                   $data[$key]['collect_address']=$value['collect_address']; 
                   $data[$key]['create_time']=date('Y-m-d H:i:s',$value['create_time']);
                }
    

        $field = array(
            'A' => array('username', '驿站名字'),
            'B' => array('distributor_id', '驿站编号'),
            'C' => array('dot_money', '网点金额'),
            'D' => array('waybill_number', '运单号'),
             'E' => array('express_name', '物流公司'),
             'F' => array('send_name', '发件人姓名'),
            'G' => array('send_phone', '发件人电话'),
            'H' => array('send_region', '发件地址'),
            'I' => array('send_address', '发件详细地址'),


            'J' => array('collect_name', '收件人姓名'),
            'K' => array('collect_phone', '收件人电话'),
            'L' => array('collect_region', '收件地址'),
            'M' => array('collect_address', '收件详细地址'),
            'N' => array('create_time', '时间'),
      
        );

        $this->phpExcelList($field, $data, '驿站关联订单表_' . date('Y-m-d'));




    }



}