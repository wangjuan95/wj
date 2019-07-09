<?php
/**
 * Distributor by PhpStorm.
 * User: Dell
 * Date: 2019/5/27
 * Time: 10:10
 */
namespace app\index\controller;

use think\Controller;
use think\Db;


class Chartview extends Controller
{

    public function index()
    {
        $res=Db::table('pre_distributor')->select();

        $arr='';
        $map='';
        $jilu=0;
        foreach($res as $key=>$v){
            //判断是否是今天
            $timetostr = date("Y-m-d H:i",$v['create_time']) ;
            $old = substr($timetostr,0,10);
            $new = date('Y-m-d');
            if($old==$new){
                $da['value']=100;
                $jilu++;
            }else{
                $da['value']=50;
            }
            $da['name']=$v['address'];

            //数据
            $arr.=json_encode($da).',';
            //经纬度

            $map.='"'.$v['address'].'"'.':'.'['.$v['longitude'].','.$v['latitude'].']'.',';
        }

        return view('',['arr'=>$arr,'map'=>$map,'i'=>$jilu]);
    }


    //折线图统计寄件派件数量

    public function linechart(){

        //最近七天的日期
        $str='';
        $data=array_values($this->get_weeks());
        foreach ($data as $key=>$value){
            $str.='"'.$value.'",';
        }
        $strr=rtrim($str,',');

        foreach ($data as $k=>$v){
            if($k<30){
                //查询寄件
                $sql= "SELECT FROM_UNIXTIME(create_time, '%Y-%m-%d'), COUNT(*) AS count FROM pre_mailing WHERE flag=1 and states=2  and  create_time BETWEEN ".strtotime($data[$k])." AND ".strtotime($data[$k+1])."
        GROUP BY FROM_UNIXTIME(create_time, 'Y-m-d') ORDER BY create_time";
                $res=Db::query($sql);
                if(isset($res[0])){
                    $arrayjijian[]=$res[0]['count'];
                }else{
                    $arrayjijian[]=0;
                }

                //查询圆通寄件
                $ysql= "SELECT FROM_UNIXTIME(create_time, '%Y-%m-%d'), COUNT(*) AS count FROM pre_mailing WHERE flag=1 and states=2  and   express_id=100102  AND create_time BETWEEN ".strtotime($data[$k])." AND ".strtotime($data[$k+1])."
        GROUP BY FROM_UNIXTIME(create_time, 'Y-m-d') ORDER BY create_time";
                $yres=Db::query($ysql);
                if(isset($yres[0])){
                    $yarrayjijian[]=$yres[0]['count'];
                }else{
                    $yarrayjijian[]=0;
                }

                //查询中通寄件
                $zsql= "SELECT FROM_UNIXTIME(create_time, '%Y-%m-%d'), COUNT(*) AS count FROM pre_mailing WHERE flag=1 and states=2  and   express_id=100101  AND create_time BETWEEN ".strtotime($data[$k])." AND ".strtotime($data[$k+1])."
        GROUP BY FROM_UNIXTIME(create_time, 'Y-m-d') ORDER BY create_time";
                $zres=Db::query($zsql);
                if(isset($zres[0])){
                    $zarrayjijian[]=$zres[0]['count'];
                }else{
                    $zarrayjijian[]=0;
                }
                //派件查询
                $paisql= "SELECT FROM_UNIXTIME(create_time, '%Y-%m-%d'), COUNT(*) AS count FROM pre_pie_depot WHERE  create_time BETWEEN ".strtotime($data[$k])." AND ".strtotime($data[$k+1])."
        GROUP BY FROM_UNIXTIME(create_time, 'Y-m-d') ORDER BY create_time";
                $paires=Db::query($paisql);
                if(isset($paires[0])){
                    $paiarray[]=$paires[0]['count'];
                }else{
                    $paiarray[]=0;
                }
            }else{
                //查询寄件
                $sqls= "SELECT FROM_UNIXTIME(create_time, '%Y-%m-%d'), COUNT(*) AS count FROM pre_mailing WHERE flag=1 and states=2  and   create_time > ".strtotime($data[$k])."  GROUP BY FROM_UNIXTIME(create_time, 'Y-m-d') ORDER BY create_time";
                $ress=Db::query($sqls);
                if(isset($ress[0])){
                    $arrayjijian[]=$ress[0]['count'];
                }else{
                    $arrayjijian[]=0;
                }

                //查询圆通寄件
                $ysqls= "SELECT FROM_UNIXTIME(create_time, '%Y-%m-%d'), COUNT(*) AS count FROM pre_mailing WHERE flag=1 and states=2  and   express_id=100102  AND create_time > ".strtotime($data[$k])."  GROUP BY FROM_UNIXTIME(create_time, 'Y-m-d') ORDER BY create_time";
                $yress=Db::query($ysqls);
                if(isset($yress[0])){
                    $yarrayjijian[]=$yress[0]['count'];
                }else{
                    $yarrayjijian[]=0;
                }

                //查询中通寄件
                $zsqls= "SELECT FROM_UNIXTIME(create_time, '%Y-%m-%d'), COUNT(*) AS count FROM pre_mailing WHERE  flag=1 and states=2  and  express_id=100101  AND create_time > ".strtotime($data[$k])."  GROUP BY FROM_UNIXTIME(create_time, 'Y-m-d') ORDER BY create_time";
                $zress=Db::query($zsqls);
                if(isset($zress[0])){
                    $zarrayjijian[]=$zress[0]['count'];
                }else{
                    $zarrayjijian[]=0;
                }

                //派件查询
                $paisqls= "SELECT FROM_UNIXTIME(create_time, '%Y-%m-%d'), COUNT(*) AS count FROM pre_pie_depot WHERE create_time > ".strtotime($data[$k])."  GROUP BY FROM_UNIXTIME(create_time, 'Y-m-d') ORDER BY create_time";
                $pairess=Db::query($paisqls);
                if(isset($pairess[0])){
                    $paiarray[]=$pairess[0]['count'];
                }else{
                    $paiarray[]=0;
                }
            }


        }

        return view('',['timedata'=>$data,'jijian'=>$arrayjijian,'paiarray'=>$paiarray,'strr'=>$strr,'yjijian'=>$yarrayjijian,'zjijian'=>$zarrayjijian]);
    }

    public  function downlode(){

        $jijian=unserialize($_POST['jijian']);
        $zjijian=unserialize($_POST['zjijian']);
        $yjijian=unserialize($_POST['yjijian']);
//        dump($jijian);die;
        $arrtime=$this->get_weeks();
        foreach ($jijian as $key => $value) {
            $data[$key]['jijian']=$value;
            $data[$key]['yjijian']=$yjijian[$key];
            $data[$key]['zjijian']=$zjijian[$key];
            $data[$key]['time']=$arrtime[$key+1];

        }

        $field = array(
            'A' => array('jijian', '总寄件量'),
            'B' => array('yjijian', '圆通寄件量'),
            'C' => array('zjijian', '中通寄件量'),
            'D' => array('time', '时间'),


        );

        $this->phpExcelList($field, $data, '寄件数据_' . date('Y-m-d'));



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

    //获取最近七天所有日期

    function get_weeks($time = '', $format='Y-m-d'){
        $time = $time != '' ? $time : time();
        //组合数据
        $date = [];
        for ($i=1; $i<=31; $i++){
            $date[$i] = date($format ,strtotime( '+' . $i-31 .' days', $time));
        }
        return $date;
    }
}
