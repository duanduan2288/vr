<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015-9-1
 * Time: 10:17
 */

namespace app\models;
use Yii;
use app\models\UploadFile;
use app\components\Util;
use yii\db\Expression;

class Helper {

    /**
     * 获取枚举类型的数据
     * @return array
     */
    public static function get_enum_value($table,$columns){
        $aa = Yii::$app->db->createCommand("SHOW COLUMNS FROM `{$table}` LIKE '{$columns}'")->queryOne();
        $type = $aa['Type'];
        $str = str_replace(['enum(',')',"'"],'',$type);
        $arr = explode(',',$str);
        return $arr;
    }

    /**
     * 数组按拼音排序
     * @param $array
     * @return array
     */
    public static function sort_array_by_word($array){
        $returndata = [];
        foreach ($array as $key=>$value)
        {
            $new_array[$key] = iconv('UTF-8', 'GBK', $value);
        }

        asort($new_array);
        foreach ($new_array as $key=>$value)
        {
            $returndata[$key] = iconv('GBK', 'UTF-8', $value);
        }
        return $returndata;
    }

    /**
     * @param $a
     * @param $b
     * @return array|mixed
     */
    public static function mergeArray($a,$b)
    {
        $args=func_get_args();
        $res=array_shift($args);
        while(!empty($args))
        {
            $next=array_shift($args);
            foreach($next as $k => $v)
            {
                if(is_integer($k))
                    isset($res[$k]) ? $res[]=$v : $res[$k]=$v;
                elseif(is_array($v) && isset($res[$k]) && is_array($res[$k]))
                    $res[$k]=self::mergeArray($res[$k],$v);
                else
                    $res[$k]=$v;
            }
        }
        return $res;
    }

    /**
     * 将base64转正图片格式
     * @param $user_id
     * @param $filename
     * @param $fileBaseStr
     * @param $fileSuffix
     * @param $agent_id
     * @return bool|string
     */
    public static function save_base_to_image($user_id,$filename,$fileBaseStr,$fileSuffix,$agent_id){
        //将base64转成图片
        $upload_dir = Yii::$app->params['upload']['attachment_root_dir'];
        !file_exists($upload_dir) ? mkdir($upload_dir,'0755',true) : '';
        $targetPath 		= $upload_dir . '/' . date('Y-m-d',time()).'/'.$user_id;
        $guid = Service::create_guid();
        $temptargetfile 	= $guid . '.' . $fileSuffix;
        !file_exists($targetPath) ? mkdir($targetPath,'0755',true) : '';
        $targetFile 		= rtrim($targetPath,'/') . '/'. $temptargetfile;
        $img = base64_decode($fileBaseStr);
        $a = file_put_contents($targetFile, $img);
        //将图片存入数据库
        $uploadFile = new UploadFile();
        $uploadFile->guid = $guid;
        $uploadFile->filename = date('Y-m-d',time()).'/'.$user_id.'/'. $temptargetfile;
        $uploadFile->filetype = $fileSuffix;
        $uploadFile->original_filename = $filename;
        $uploadFile->upload_role = '注册商';
        $uploadFile->company_id = $agent_id;
        $uploadFile->user_id = $user_id;
        $uploadFile->ip = Util::get_ip();
        $uploadFile->created = new Expression('NOW()');
        if($uploadFile->save()){
            return $uploadFile->guid;
        }else{
            return false;
        }
    }

    /**
     * 获取浏览器类型
     * @return string
     */
    public static function my_get_browser()
    {
        if(empty($_SERVER['HTTP_USER_AGENT'])){

            return 'Unknown';
        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Firefox')){

            return 'Firefox';

        }

        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Chrome')){

            return 'Chrome';

        }

        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Safari')){

            return 'Safari';

        }

        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Opera')){

            return 'Opera';

        }
        if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'360SE')){

            return '360SE';

        }else{
            return 'IE';
        }
    }
} 