<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015-8-27
 * Time: 16:20
 */
namespace app\extensions;
use Yii;
use app\models\MessageLog;
use yii\base\Exception;

class UcloudSms {

    /**
     * 发送短信
     * @param $phones array()
     * @param $contents string
     */
    public static function sendSms($phones, $contents,$domain,$user_id='-1'){

        $corp_id = Yii::$app->params['admin_phones']['corp_id'];
        $corp_pwd = Yii::$app->params['admin_phones']['corp_pwd'];
        $corp_service = Yii::$app->params['admin_phones']['corp_service'];
        $url = Yii::$app->params['admin_phones']['url'];

        $arr = [];
        $arr['corp_id'] = $corp_id;
        $arr['corp_pwd'] = $corp_pwd;
        $arr['corp_service'] = $corp_service;
        $arr['msg_content'] = iconv('UTF-8','GBK//IGNORE',$contents);
        $arr['mobile'] = $phones;
        $arr['corp_msg_id'] = '';
        $arr['ext'] = '';
        $data = UcloudSms::curl_get($url,http_build_query($arr));
        if(strpos($data,'#')==false){
            self::save_message_log($phones,$contents,$domain,'失败',$user_id,$data);
            return false;
        }else{
            self::save_message_log($phones,$contents,$domain,'成功',$user_id);
            return true;
        }
    }

    /**
     * 发送短信日志
     * @param $phone
     * @param $content
     * @param $domain
     * @param $status
     * @param $user_id
     * @param string $fail_reason
     */
    public static function save_message_log($phone,$content,$domain,$status,$user_id,$fail_reason=''){

        $emaillog = new MessageLog();
        $emaillog->to = $phone;
        $emaillog->from = 'ucloud';
        $emaillog->content  = $content;
        $emaillog->domain  = $domain;
        $emaillog->status = $status;
        $emaillog->creator = $user_id;
        $emaillog->fail_reason = $fail_reason;
        $emaillog->created = date('Y-m-d H:i:s');
        $emaillog->save();
    }

    /**
     * @param $path string
     * @return mixed
     */
    public static function curl_get($path,$post_data){
        try{
            // 创建一个cURL资源
            $ch = curl_init();
            // 设置URL和相应的选项
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_URL, $path);
            curl_setopt($ch,CURLOPT_HTTPHEADER,array(
                'Content-type:application/x-www-form-urlencoded;charset=gbk',
                'Accept-Encoding:gbk'
            ));
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            // 抓取URL并把它传递给浏览器
            $res =  curl_exec($ch);
            if($res==false){
                $res = curl_error($ch);
            }
            // 关闭cURL资源，并且释放系统资源
            curl_close($ch);
            return $res;
        }catch (Exception $e){
            return $e->getMessage();
        }
    }

    public static function url_encode(&$value){
        $value = urlencode($value);
    }


} 