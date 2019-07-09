<?php
/**
 * Escel by PhpStorm.
 * User: Dell
 * Date: 2019/6/13
 * Time: 10:40
 */
namespace app\index\controller;
use think\Controller;
use think\Db;

class Excel extends Controller
{

	public function index(){
			$file='./excel.xls';

			Vendor('PHPExcel.PHPExcel');    //包的存放是\ThinkPHP\Extend\Vendor\PHPExcel
			$objReader = \PHPExcel_IOFactory::createReaderForFile($file); 
			//准备打开文件

			$objPHPExcel = $objReader->load($file); //载入文件

	        $sheet = $objPHPExcel->getSheet(0); 
	        // 取得总行数 
	        $highestRow = $sheet->getHighestRow();     
	        // 取得总列数      
	        $highestColumn = $sheet->getHighestColumn(); 
	        //循环读取excel文件,读取一条,插入一条
	      
	         $allColumn = $sheet->getHighestColumn();        //**取得最大的列号*/
	         $allRow = $sheet->getHighestRow();        //**取得一共有多少行*/
	         $ColumnNum = \PHPExcel_Cell::columnIndexFromString($allColumn);     
	         // 列号 转 列数
	         
	         $data = array();
	         for($rowIndex=1;$rowIndex<=$allRow;$rowIndex++){        
	         //循环读取每个单元格的内容。注意行从1开始，列从A开始
	             for($colIndex=0;$colIndex<=$ColumnNum;$colIndex++){
	                 $data[$rowIndex][] =(string)$sheet->getCellByColumnAndRow($colIndex, $rowIndex)->getValue();  
	             }
	         }
	       dump($data);  
	       foreach($data as $k=>$v){
	       	$arr=[];
	       		$arr=[
	       			'order'=>$v[0],
	       			'order_time'=>$v[1],
	       			'wuliunum'=>$v[2],
	       			'waybill_number'=>$v[3],
	       			'sendname'=>$v[4],
	       			'sendaddr'=>$v[5],
	       			'sendprovince'=>$v[6],
	       			'phone'=>$v[7],
	       			'recname'=>$v[8],
	       			'recaddr'=>$v[9],
	       			'recprovince'=>$v[10],
	       			'type'=>$v[11],
	       			'weight'=>$v[12],
	       			'jifeiweight'=>$v[13],
	       			'shouzhong'=>$v[14],
	       			'xuzhong'=>$v[15],
	       			'monery'=>$v[16],
	       			'jiedancondition'=>$v[17],
	       			'jiedanname'=>$v[18],
	       			'jiedancode'=>$v[19],
	       			'lanshoucondition'=>$v[20],
	       			'lanshounet'=>$v[21],
	       			'lanshoucode'=>$v[22],
	       			'qianshou_time'=>$v[23],
	       			'lanshou_time'=>$v[24],
	       			'paijianname'=>$v[26],
	       			'paijiancode'=>$v[25],
	       			'jiedantime'=>$v[27],
	       			'yewucode'=>$v[28],





	       		];
	       		// $res=Db::connect('db_con2')->name('bill')->insert($arr);
	       } 
	}

}