<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use phpseclib\Net\SFTP;
use alexgx\phpexcel\PhpExcel;
use app\models\Service;
use app\models\DataDomain;
use app\models\DataFinance;
use yii\db\Expression;

class BeilonglogController extends Controller
{
    public function actionIndex()
    {
        header("Content-Type: text/html;charset=utf-8");
    }

    public function actionDataFinance($t='')
    {
        header("Content-Type: text/html;charset=utf-8");
        if(!$t){
            $t = date('Ymd',strtotime("-1 day"));//t的写法为 年月日：20141001
        }
        $sftp = new SFTP('202.173.11.179');  //初始化一个sftp实例
        if ($sftp->login('huyi', 'emu0qZLObd')) {
            $sftp->chdir('xn--czr694b');
            $sftp->chdir('fee_log');
            if(!$sftp->chdir($t)) die('所查询的日期无商标提交！');
            $allfiles = $sftp->nlist();
            foreach ($allfiles as $filename) { //遍历一遍目录下的文件与文件夹
                if (in_array($filename,array('.','..'))) continue; //无视 . 与 ..
                $remote_file = '/xn--czr694b/fee_log/'.$t.'/'.$filename;
                $local_file = '/domain_feelog/'.$t.'/'.$filename;

                if (!file_exists('/domain_feelog')){ mkdir ('/domain_feelog');}
                if (!file_exists('/domain_feelog/'.$t)){ mkdir ('/domain_feelog/'.$t);}

                if (!is_dir($remote_file)) { //不是目录的话继续
                    if(!$sftp->get($remote_file, $local_file))
                    {
                        $err = $sftp->getSFTPErrors();
                        $error = $err[0];
                        echo $filename.' '.$error."<br/>";
                        continue;
                    }else{
                        $filePath = $local_file; // 要读取的文件的路径

                        $PHPExcel = new PHPExcel(); // 拿到实例，待会儿用

                        $ExcelFile=$PHPExcel->load($filePath); // Reader读出来后，加载给Excel实例

                        $currentSheet = $ExcelFile->getSheet(0); // 拿到第一个sheet（工作簿？）

                        $allColumn = $currentSheet->getHighestColumn(); // 最高的列，比如AU. 列从A开始

                        $allRow = $currentSheet->getHighestRow(); // 最大的行，比如12980. 行从0开始

                        for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
                            $lineVal = [];

                            for ($currentColumn="A"; $currentColumn <= $allColumn; $currentColumn++) {
                                $val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65, $currentRow)->getValue(); // ord把字母转为ascii码，A->65, B->66....这儿的坑在于AU->65, 后面的U没有计算进去，所以用索引方式遍历是有缺陷的。
                                array_push($lineVal,$val);
                            }

                            $DataFinance = new DataFinance();
                            $DataFinance->registrar_name = $lineVal[1];
                            $DataFinance->operation_type = $lineVal[2];
                            $DataFinance->sequence_number = intval($lineVal[3]);
                            $DataFinance->domain_name = ($lineVal[4])?$lineVal[4]:NULL;
                            $DataFinance->operation_deadline = intval($lineVal[5]);
                            $DataFinance->cost = intval($lineVal[6]);
                            $DataFinance->cost_type = $lineVal[7];
                            $DataFinance->operator = $lineVal[8];
                            $DataFinance->operation_date = $lineVal[9];
                            $DataFinance->service_start_time = $lineVal[10];
                            $DataFinance->service_end_time = $lineVal[11];
                            if(count($lineVal)>12)
                                $DataFinance->remarks = $lineVal[12];
                            $flag = $DataFinance->save();
                            if(!$flag){
                                print_r($lineVal);
                            }
                        }
                    }
                }
            }
        }else{
            exit('Sftp Login Failed');
        }
    }

    public function actionDataDomain($t='')
    {
        header("Content-Type: text/html;charset=utf-8");
        if(!$t){
            $t = date('Ymd',strtotime("-1 day"));//t的写法为 年月日：20141001
        }
        $sftp = new SFTP('202.173.11.179');  //初始化一个sftp实例
        if ($sftp->login('huyi', 'emu0qZLObd')) {
            $sftp->chdir('xn--czr694b');
            $sftp->chdir('trans_log');
            if(!$sftp->chdir($t)) die('所查询的日期无商标提交！');
            $allfiles = $sftp->nlist();
            foreach ($allfiles as $filename) { //遍历一遍目录下的文件与文件夹
                if (in_array($filename,array('.','..'))) continue; //无视 . 与 ..
                $remote_file = '/xn--czr694b/trans_log/'.$t.'/'.$filename;
                $local_file = '/domain_translog/'.$t.'/'.$filename;

                if (!file_exists('/domain_translog')){ mkdir ('/domain_translog');}
                if (!file_exists('/domain_translog/'.$t)){ mkdir ('/domain_translog/'.$t);}

                if (!is_dir($remote_file)) { //不是目录的话继续
                    if(!$sftp->get($remote_file, $local_file))
                    {
                        $err = $sftp->getSFTPErrors();
                        $error = $err[0];
                        echo $filename.' '.$error."<br/>";
                        continue;
                    }else{
                        $filePath = $local_file; // 要读取的文件的路径

                        $PHPExcel = new PHPExcel(); // 拿到实例，待会儿用

                        $ExcelFile=$PHPExcel->load($filePath); // Reader读出来后，加载给Excel实例

                        $currentSheet = $ExcelFile->getSheet(0); // 拿到第一个sheet（工作簿？）

                        $allColumn = $currentSheet->getHighestColumn(); // 最高的列，比如AU. 列从A开始

                        $allRow = $currentSheet->getHighestRow(); // 最大的行，比如12980. 行从0开始

                        for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
                            $lineVal = [];

                            for ($currentColumn="A"; $currentColumn <= $allColumn; $currentColumn++) {
                                $val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65, $currentRow)->getValue(); // ord把字母转为ascii码，A->65, B->66....这儿的坑在于AU->65, 后面的U没有计算进去，所以用索引方式遍历是有缺陷的。
                                array_push($lineVal,$val);
                            }

                            $DataDomain = new DataDomain();
                            $DataDomain->domain_name = $lineVal[1];
                            $DataDomain->registrar_id = $lineVal[2];
                            $DataDomain->registered_person = strval($lineVal[3]);
                            $DataDomain->command = $lineVal[4];
                            $DataDomain->operation_deadline = intval($lineVal[5]);
                            $DataDomain->operation_date = $lineVal[6];
                            $DataDomain->roll_registrar_id = $lineVal[7];
                            $DataDomain->active_stage = $lineVal[8];
                            $DataDomain->active_stage_name = $lineVal[9];
                            $DataDomain->custom_active_stage_name = $lineVal[10];
                            $DataDomain->is_change = $lineVal[11];
                            $flag = $DataDomain->save();
                            if(!$flag){
                                print_r($lineVal);
                            }
                        }
                    }
                }
            }
        }else{
            exit('Sftp Login Failed');
        }
    }
    
    public function actionAllFinance()
    {
        header("Content-Type: text/html;charset=utf-8");
        $sftp = new SFTP('202.173.11.179');  //初始化一个sftp实例
        if ($sftp->login('huyi', 'emu0qZLObd')) {
            $sftp->chdir('xn--czr694b');
            $sftp->chdir('fee_log');
            $times = $sftp->nlist();
            foreach ($times as $value) {
                if (in_array($value,array('.','..'))) continue; //无视 . 与 ..
                $sftp->chdir($value);
                $allfiles = $sftp->nlist();
                foreach ($allfiles as $filename) { //遍历一遍目录下的文件与文件夹
                    if (in_array($filename,array('.','..'))) continue; //无视 . 与 ..
                    $remote_file = '/xn--czr694b/fee_log/'.$value.'/'.$filename;
                    $local_file = '/domain_feelog/'.$value.'/'.$filename;

                    if (!file_exists('/domain_feelog')){ mkdir ('/domain_feelog');}
                    if (!file_exists('/domain_feelog/'.$value)){ mkdir ('/domain_feelog/'.$value);}

                    if (!is_dir($remote_file)) { //不是目录的话继续
                        if(!$sftp->get($remote_file, $local_file))
                        {
                            $err = $sftp->getSFTPErrors();
                            $error = $err[0];
                            echo $filename.' '.$error."<br/>";
                            continue;
                        }else{
                            $filePath = $local_file; // 要读取的文件的路径

                            $PHPExcel = new PHPExcel(); // 拿到实例，待会儿用

                            $ExcelFile=$PHPExcel->load($filePath); // Reader读出来后，加载给Excel实例

                            //$allSheet = $ExcelFile->getSheetCount(); // sheet数

                            $currentSheet = $ExcelFile->getSheet(0); // 拿到第一个sheet（工作簿？）

                            $allColumn = $currentSheet->getHighestColumn(); // 最高的列，比如AU. 列从A开始

                            $allRow = $currentSheet->getHighestRow(); // 最大的行，比如12980. 行从0开始

                            for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
                                $lineVal = [];

                                for ($currentColumn="A"; $currentColumn <= $allColumn; $currentColumn++) {
                                    $val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65, $currentRow)->getValue(); // ord把字母转为ascii码，A->65, B->66....这儿的坑在于AU->65, 后面的U没有计算进去，所以用索引方式遍历是有缺陷的。
                                    //array_push($lineVal, iconv("UTF-8","GB2312//ignore",$val));
                                    array_push($lineVal,$val);
                                }

                                $DataFinance = new DataFinance();
                                $DataFinance->registrar_name = $lineVal[1];
                                $DataFinance->operation_type = $lineVal[2];
                                $DataFinance->sequence_number = intval($lineVal[3]);
                                $DataFinance->domain_name = ($lineVal[4])?$lineVal[4]:NULL;
                                $DataFinance->operation_deadline = intval($lineVal[5]);
                                $DataFinance->cost = intval($lineVal[6]);
                                $DataFinance->cost_type = $lineVal[7];
                                $DataFinance->operator = $lineVal[8];
                                $DataFinance->operation_date = $lineVal[9];
                                $DataFinance->service_start_time = $lineVal[10];
                                $DataFinance->service_end_time = $lineVal[11];
                                if(count($lineVal)>12)
                                    $DataFinance->remarks = $lineVal[12];
                                $flag = $DataFinance->save();
                                if(!$flag){
                                    print_r($lineVal);
                                }
                            }
                        }
                        echo $filename." success<br/>";
                    }
                }
                $sftp->chdir('..');
            }
        }else{
            exit('Sftp Login Failed');
        }
    }

    public function actionAllDomain()
    {
        header("Content-Type: text/html;charset=utf-8");
        $sftp = new SFTP('202.173.11.179');  //初始化一个sftp实例
        if ($sftp->login('huyi', 'emu0qZLObd')) {
            $sftp->chdir('xn--czr694b');
            $sftp->chdir('trans_log');
            $times = $sftp->nlist();
            foreach ($times as $value) {
                if (in_array($value,array('.','..'))) continue; //无视 . 与 ..
                $sftp->chdir($value);
                $allfiles = $sftp->nlist();
                foreach ($allfiles as $filename) { //遍历一遍目录下的文件与文件夹
                    if (in_array($filename,array('.','..'))) continue; //无视 . 与 ..
                    $remote_file = '/xn--czr694b/trans_log/'.$value.'/'.$filename;
                    $local_file = '/domain_translog/'.$value.'/'.$filename;

                    if (!file_exists('/domain_translog')){ mkdir ('/domain_translog');}
                    if (!file_exists('/domain_translog/'.$value)){ mkdir ('/domain_translog/'.$value);}

                    if (!is_dir($remote_file)) { //不是目录的话继续
                        if(!$sftp->get($remote_file, $local_file))
                        {
                            $err = $sftp->getSFTPErrors();
                            $error = $err[0];
                            echo $filename.' '.$error."<br/>";
                            continue;
                        }else{
                            $filePath = $local_file; // 要读取的文件的路径

                            $PHPExcel = new PHPExcel(); // 拿到实例，待会儿用

                            $ExcelFile=$PHPExcel->load($filePath); // Reader读出来后，加载给Excel实例

                            $currentSheet = $ExcelFile->getSheet(0); // 拿到第一个sheet（工作簿？）

                            $allColumn = $currentSheet->getHighestColumn(); // 最高的列，比如AU. 列从A开始

                            $allRow = $currentSheet->getHighestRow(); // 最大的行，比如12980. 行从0开始

                            for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
                                $lineVal = [];

                                for ($currentColumn="A"; $currentColumn <= $allColumn; $currentColumn++) {
                                    $val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65, $currentRow)->getValue(); // ord把字母转为ascii码，A->65, B->66....这儿的坑在于AU->65, 后面的U没有计算进去，所以用索引方式遍历是有缺陷的。
                                    array_push($lineVal,$val);
                                }

                                $DataDomain = new DataDomain();
                                $DataDomain->domain_name = $lineVal[1];
                                $DataDomain->registrar_id = $lineVal[2];
                                $DataDomain->registered_person = strval($lineVal[3]);
                                $DataDomain->command = $lineVal[4];
                                $DataDomain->operation_deadline = intval($lineVal[5]);
                                $DataDomain->operation_date = $lineVal[6];
                                $DataDomain->roll_registrar_id = $lineVal[7];
                                $DataDomain->active_stage = $lineVal[8];
                                $DataDomain->active_stage_name = $lineVal[9];
                                $DataDomain->custom_active_stage_name = $lineVal[10];
                                $DataDomain->is_change = $lineVal[11];
                                $flag = $DataDomain->save();
                                if(!$flag){
                                    print_r($lineVal);
                                    //yii::error($DataDomain->errors,'command');
                                    //yii::error($lineVal[3],'command');
                                }
                            }
                        }
                    }
                }
                $sftp->chdir('..');
            }
        }else{
            exit('Sftp Login Failed');
        }
    }
    
}
