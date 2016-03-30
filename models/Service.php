<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-11-18
 * Time: 下午2:30
 */
namespace app\models;
use Yii;
use yii\db\Query;
use yii\web\Cookie;
use app\models\UploadFile;
use app\models\RegistryUser;
use app\components\Util;
use yii\db\Expression;
use app\models\EmailLog;

class Service {
    /**
     * 随机产生A-Z, a-z, 0-9的字符串
     * @param int $length 随机数的长度
     * @return string
     */
    public static function random_hash($length = 8)
    {
        $salt = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
        $count = count($salt);
        $hash = '';
        for ($i = 0; $i < $length; $i++) {
            $hash .= $salt[mt_rand(0, $count-1)];
        }
        return $hash;
    }

    public static function create_password($password)
    {
        return md5($password);
    }

    /**
     * 生成token
     * @param $username
     * @param $password
     * @return string
     */
    public static function create_token($username, $password,$type='')
    {
        $time = round(microtime(true)*1000);
        $token = md5($username.$password.$time.self::random_hash(8));

        if(self::get_uid_by_token($token,$type)){
            self::create_token($username, $password,$type);
        }
        return $token;
    }

    /**
     * 通过token获取uid
     */
    public static function get_uid_by_token($token,$type='')
    {
        return Yii::$app->redis->get(self::get_pc_uid_by_token_key($token,$type));
    }

    /**
     * token 对应用户 id key
     */
    public static function get_pc_uid_by_token_key($token,$type='')
    {
        return $type.'user.pc.token:'.$token;
    }

    /**
     * pc用户 uid 对应 token key
     */
    public static function get_token_by_pc_uid_key($uid,$type='')
    {
        return $type.'user:'.$uid.':pc.token';
    }

    /**
     * 生成订单编号  6位代理商id+精确到毫秒的时间戳
     * @param  [type] $agent_id [description]
     * @return [type]           [description]
     */
    public static function create_order_number($agent_id)
    {
        $num = 6 - strlen($agent_id);
        $agent_id = str_repeat('0', $num) . $agent_id;
        $time = explode ( " ", microtime () );  
        $time = $time [1] . ($time [0] * 1000);  
        $time2 = explode ( ".", $time );  
        $time = $time2 [0];
        return $agent_id.$time;
    }

    /**
     * 登陆后的关联操作
     * @param  int  $id 用户id
     * return  string | true   登陆成功返回true  不成功返回错误 信息字符串
     */
    public static function do_login($info,$type='')
    {
        $id = $info->id;
        //生成登录token
        $token = self::create_token($info->email, $info->password,$type);

        //添加token和uid关系
        $oldtoken = yii::$app->redis->get(self::get_token_by_pc_uid_key($id,$type));
        if($oldtoken){
            //删除原来的token
            yii::$app->redis->del(self::get_pc_uid_by_token_key($oldtoken,$type));
        }
        yii::$app->redis->setex(self::get_pc_uid_by_token_key($token,$type), 2592000, $id);
        yii::$app->redis->setex(self::get_token_by_pc_uid_key($id,$type), 2592000, $token);

       // $num = Service::get_unread_notification($id);

        // $notificationcookie = new CHttpCookie('notification', 0);
        // $notificationcookie->expire = time()+2592000;  //有效期30天
        // Yii::$app->request->cookies['notification']=$notificationcookie;
        
        //生成cookie
        $cookie = new Cookie(['name'=>$type.'token']);
        $cookie->value = $token;
        $cookie->path = '/';
        $cookie->expire = 0;
        Yii::$app->getResponse()->getCookies()->add($cookie);

        $uidcookie = new Cookie(['name'=>$type.'uid']);
        $uidcookie->value = $id;
        $uidcookie->path = '/';
        $uidcookie->expire = 0;

        Yii::$app->getResponse()->getCookies()->add($uidcookie);

        if(empty($type)){
            $emailcookie = new Cookie(['name'=>'username']);
            $emailcookie->value = $id;
            $emailcookie->path = '/';
            $cookie->expire = 0;
            Yii::$app->getResponse()->getCookies()->add($emailcookie);
            //记录登录信息
            self::add_login_log($info);
        }
        return true;
    }

    /**
     * 用户登录日志
     */
    public static function add_login_log($user)
    {
        $user->last_login_time = date('Y-m-d H:i:s');
        $user->last_login_ip = Util::get_ip();
        $user->login_count = ($user->login_count +1);
        $user->save();
        $log = new LoginLog();
        $log->user_id = $user->id;
        $log->login_ip = Util::get_ip();
        $log->created = date('Y-m-d H:i:s');
        $log->type = '用户登录';
        return $log->save();
    }

    /**
     * 发送邮件
     */
    public static function send_email($uid, $type)
    {
        if (empty($uid) || empty($type)) {
            return false;
        }
        $user = RegistryUser::findOne(['id'=>$uid]);
        if (empty($user)) {
            return false;
        }

        $mail = array();
        $mail['created']=time();

        switch($type){
            case 'activate':
                // 激活邮件
                $code = self::hash_encode($user->email.$user->salt);
                if($uid<100000){
                    $url = 'http://'.$_SERVER['SERVER_NAME'].'/site/activate?code='.$code.'&email='.$user->email;
                }else{
                    $url = 'http://reseller.itmnic.com/site/activate?code='.$code.'&email='.$user->email;
                }
                $controller = yii::$app->getController();
                $view_file = self::get_mail_view('activate');
                $body = $controller->renderFile($view_file, array('url'=>$url, 'user'=>$user), true);
                $subject = '账号激活';
                break;

            case 'found':
                // 找回密码
                $code = self::hash_encode($user->email.$user->salt);
                $url = 'http://'.$_SERVER['SERVER_NAME'].'/site/change?code='.$code.'&email='.$user->email;
                $controller = yii::$app->getController();
                $view_file = self::get_mail_view('foundpassword');
                $body = $controller->renderFile($view_file, array('url'=>$url, 'info'=>$user, 'time'=>time()), true);
                $subject = '找回密码';
                break;
            default:
                return false;
        }
        $mail['data'] = array(
            'receiver'=>$user->email,
            'subject'=>$subject,
            'body'=>$body
        );
        $res = self::sendMail(array($user->email), $subject, $body);
        if ($res) {
            return true;
        }
        return false;
    }

    /**
     * 发送邮件
     * @param array $address
     * @param string $subject
     * @param string $body
     */
    public static function sendMail(array $address, $subject, $body,$domain='',$user_id = ''){

        $send = Yii::$app->mailer->compose()
            ->setTo($address)
            ->setSubject($subject)
            ->setFrom(["noreply@itmnic.com"=>'注册局'])
            ->setHtmlBody($body)
            ->send();

        $status = '失败';
        $fail_reason = $send;
        if(true==$send){
            $status = '成功';
            $fail_reason = '';
        }
        self::save_email_log(json_encode($address),$subject,$body,$domain,$status,$user_id,$fail_reason);
        return $send;
    }

    /**
     * 保存邮件发送日志
     * @param $email
     * @param $subject
     * @param $body
     * @param $domain
     * @param $status
     * @param $user_id
     * @param string $fail_reason
     */
    public static function save_email_log($email,$subject,$body,$domain,$status,$user_id,$fail_reason=''){
        $username = 'noreply@itmnic.com';
        $emaillog = new EmailLog();
        $emaillog->to = $email;
        $emaillog->from = $username;
        $emaillog->subject  = $subject;
        $emaillog->body  = $body;
        $emaillog->domain  = $domain;
        $emaillog->status = $status;
        $emaillog->creator = $user_id;
        $emaillog->fail_reason = $fail_reason;
        $emaillog->created = date('Y-m-d H:i:s');
        $emaillog->save();
    }
    /**
     * [send_notification description]
     * @param  integer $type        1代表发送单条，2代表发送到含有菜单的用户
     * @param  [type]  $system_type 系统类型，注册商or注册局
     * @param  string  $msg         消息标题
     * @param  integer $creator     发送者
     * @param  integer $receiver    接收者
     * @param  string  $priority    紧急程度，普通or紧急
     * @param  integer $issue_id    工单ID
     * @param  string  $content     消息内容
     * @param  string  $menu        菜单名称
     * @param  integer $company_id  注册商公司ID
     * @return [type]               [description]
     */
    public static function send_notification($type=1,$system_type,$msg,$creator=0,$receiver=0,$priority='普通',$issue_id=0,$content='',$menu='',$company_id=0)
    {
        $uid = Yii::$app->user->id;
        if (!empty($uid)){
            if (empty($msg))
                return false;
            if ($type == 1) {
                return self::save_notification($msg,$content,$issue_id,$creator,$receiver,$priority);
            }elseif ($type == 2) {
                if (empty($system_type) || empty($menu) || !in_array($system_type, array('注册局','注册商')))
                    return false;
                switch ($system_type) {
                    case '注册商':
                        $company_info = Registrar::findOne($company_id);
                        if(empty($company_info)) return false;
                        $sql = "SELECT r.id FROM agent_user r
                                    INNER JOIN  auth_user_role aur ON r.id = aur.user_id
                                    INNER JOIN  auth_role_menu arm ON aur.role_id = arm.role_id
                                    INNER JOIN  auth_menu ON arm.menu_id = auth_menu.id AND auth_menu.name='{$menu}' AND auth_menu.platform = '代理商'
                                    WHERE r.agent_id = {$company_id}";
                        $db = Yii::app ()->db->createCommand ( $sql );
                        $users = $db->queryAll ();
                        if (empty($users)) return false;
                        $creator = !empty($creator) ? $creator : $uid;
                        foreach ($users as $key => $value) {
                            $b = self::save_notification($msg,$content,$issue_id,$creator,$value['id'],$priority);
                        }
                        return true;
                        break;

                    case '注册局':
                        $sql = "SELECT r.id FROM registry_user r
                                    INNER JOIN  auth_user_role aur ON r.id = aur.user_id
                                    INNER JOIN  auth_role_menu arm ON aur.role_id = arm.role_id
                                    INNER JOIN  auth_menu ON arm.menu_id = auth_menu.id AND auth_menu.name='{$menu}' AND auth_menu.platform = '注册商'";
                        $db = Yii::app ()->db->createCommand ( $sql );
                        $users = $db->queryAll ();
                        if (empty($users)) return false;
                        $creator = !empty($creator) ? $creator : $uid;
                        foreach ($users as $key => $value) {
                            $b = self::save_notification($msg,$content,$issue_id,$creator,$value['id'],$priority);
                        }
                        return true;
                        break;
                }
            }
        }else{
            return false;
        }
    }

    /**
     * 保存消息
     * @param  [type]  $title    [description]
     * @param  string  $content  [description]
     * @param  integer $issue_id [description]
     * @param  [type]  $creator  [description]
     * @param  [type]  $receiver [description]
     * @param  [type]  $priority [description]
     * @return [type]            [description]
     */
    public static function save_notification($title,$content='',$issue_id=0,$creator,$receiver,$priority)
    {
        $model = new Notification;
        $model->title = $title;
        $model->content = $content;
        $model->issue_id = intval($issue_id);
        $model->creator = $creator;
        $model->receiver = $receiver;
        $model->priority = $priority;
        $model->created =  new Expression('NOW()');
        $model->save();
        return $model->save();
        // return  $model->errors;
    }

    /**
     * 取email 模板
     */
    public static function get_mail_view($name)
    {
        return yii::$app->getViewPath().'/mails/'.$name.'.php';
    }

    /**
     * 加密码hash，用于激活验证
     * @param string $hash
     */
    public static function hash_encode($hash)
    {
        $md5 = md5($hash);
        return substr($md5, -2).substr($md5, 2,28).substr($md5, 0, 2);
    }

    /**
     * 公共获取uid方法
     */
    public static function get_uid()
    {
        $cookie = Yii::$app->request->getCookies();
        $token 	= isset($cookie['token']->value) ? $cookie['token']->value : '';
        $supertoken = isset($cookie['supertoken']->value) ? $cookie['supertoken']->value : '';

        if(empty($token) && empty($supertoken)){
            return '';
        }

        if(substr($token, 0, 6)=='guest_' && substr($supertoken, 0, 6)=='guest_'){
            return '';
        }

        $uid = self::get_uid_by_token($token);
        $superuid = self::get_uid_by_token($supertoken,'super');

        if(empty($uid) && empty($superuid)){
            return '';
        }

        return !empty($superuid)?$superuid:$uid;
    }

    public static function post($url, $post_data = '', $timeout = 60)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
        if($post_data != ''){
           curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        // curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }

    /**
     * 删除用户 cookie
     */
    public static function del_user_cookie()
    {
        yii::$app->response->cookies->remove('token');
        yii::$app->response->cookies->remove('uid');
        yii::$app->response->cookies->remove('supertoken');
        yii::$app->response->cookies->remove('superuid');
    }


    /**
     * 获取角色名称
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function getRoleName($id)
    {
        $str = '---';
        $info = AuthRole::findOne(['id'=>$id]);
        if (!empty($info)&&isset($info['name'])) {
            $str = $info['name'];
        }
        return $str;
    }

    /**
     * 根据id找用户所在表
     * @param $id
     * @return CActiveRecord
     */
    public static function get_user_model($id)
    {
        $model = RegistryUser::findOne(['id'=>$id]);
        return $model;
    }

    /**
     * 获取用户名
     * @param $id
     * @return mixed|null|string|void
     */
    public static function get_user_name($id,$type=null)
    {
        $model = User::findOne(['id'=>$id]);
        if($model){
            return $model->logonName;
        }else{
            return '--';
        }
    }

    /**
     * 生成guid
     * @return string
     */
    public static function create_guid()
    {
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            //chr(123)// "{"
            $uuid = substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
            //.chr(125);// "}"
            return $uuid;
        }
    }

    /**
     * 返回随机密码
     * @param int $length
     * @return string
     */
    public static function get_rand_password($length = 4)
    {
        $seeds = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        $numseeds = '0123456789';
        $seeds_count = strlen ( $seeds );
        $numseeds_count = strlen($numseeds);
        for($i = 0; $length > $i; $i ++) {
            $str .= $seeds {mt_rand ( 0, $seeds_count - 1 )};
            $str.= $numseeds{mt_rand ( 0, $numseeds_count - 1 )};
        }
        return $str;
    }

    public static function get_issue_process_time($issue_id,$status)
    {
        $sql = 'SELECT created FROM `issue_operation` WHERE issue_id = "'.$issue_id.'" AND status = "'.$status.'" ORDER BY created ASC limit 0,1';
        $time = Yii::$app->db->createCommand($sql)->queryScalar();
        if($time){
            return $time;
        }else{
            return '--';
        }
    }

    public static function get_issue_process_content($issue_id)
    {
        $sql = 'SELECT `attached_data` FROM `issue_operation` WHERE issue_id = "'.$issue_id.'" ORDER BY created desc limit 0,1';
        $operation = Yii::$app->db->createCommand($sql)->queryRow();
        if($operation){
            $attached_data = json_decode($operation['attached_data'], true);
            $content = isset($attached_data['content'])?$attached_data['content'] : '---';
            // return $content;
            return mb_strlen($content)>6?mb_substr($content,0,6, 'utf-8').'...':$content;
        }else{
            return '---';
        }
    }

    public static function substr_cut($str_cut,$length)
    {
        if (strlen($str_cut) > $length)
        {
            for($i=0; $i < $length; $i++)
            if (ord($str_cut[$i]) > 128)    $i++;
            $str_cut = substr($str_cut,0,$i)."..";
        }
        return $str_cut;
    }

    /**
     * 获取公司名称
     * @param $company_id
     * @return mixed|null|string|void
     */
    public static function get_company_name_by_id($company_id)
    {
        if($company_id==0){
            return '注册局';
        }else{
            $agent = Registrar::findOne($company_id);
            if($agent){
                return $agent->company_name_zh_cn;
            }else{
                return '--';
            }
        }
    }

    /**获取代理商编码
     * @param $contact_id
     * @return string
     */
    public static function get_agent_code_by_contact($contact_id){
        $agent_code = substr($contact_id,4,3);
        return $agent_code;
    }

    /**
     * 剩余时间
     * @param $start_time
     * @return string
     */
    public static function get_remaining_days($time){

        $end_time   = strtotime('+10 day',strtotime($time));
        $now        = time();
        $time_past  = $end_time - $now;

        $time_past = floor($time_past/3600);
        if($time_past<=0){
            return false;
        }
        if($time_past<24){
            return floor($time_past).'小时';
        }else{
            $str = floor($time_past/24).'天';
            $second = $time_past%24;//除去整天之后剩余的时间
            $str .= floor($second).'小时';
            return $str;
        }
    }
    /**
     * 通过公司名称获取公司ID
     * @param $name
     * @return mixed|null|string|void
     */
    public static function get_company_id_by_name($name)
    {
        $query = new Query();
        $query  ->select('id')
                ->from('agent')
                ->where("company_name_zh_cn like '%{$name}%'");
        $company_ids = $query->createCommand()->queryColumn();
        return !empty($company_ids)?implode(',', $company_ids):'-1';
    }


    /**
     * 获取未读信息
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function get_unread_notification($uid)
    {
        $num = Notification::model()->count("receiver = {$uid} and status = '未读'");
        $cookie = new CHttpCookie('notification', $num);
        // $cookie->expire = time()+600;  //有效期10分钟
        Yii::$app->request->cookies['notification']=$cookie;
        return $num;
    }

    /**
     * 获取文件信息
     * @param $guid
     * @param $type
     * @return array|bool
     */
    public static function _get_file($guid,$type)
    {
        $file = UploadFile::findOne(['guid'=>$guid]);
        if(null!==$file){
            $upload_dir = Yii::$app->params['upload']['attachment_root_dir'];
            $filepath   = $upload_dir . '/' . $file->filename;
            if(!file_exists($filepath)){
                return false;
            }
            if(@filesize($filepath)>2*1024*1024){
                $newname = str_replace($guid,$guid.'_new',$filepath);
                if($file->filetype=='jpg'){
                    $image = @imagecreatefromjpeg($filepath);
                    imagejpeg($image,$newname,1);
                }
                if($file->filetype=='png'){
                    $image = @imagecreatefrompng($filepath);
                    imagepng($image,$newname,1);
                }
                $filepath = $newname;
            }
            $fp = fopen($filepath, 'rb',0);
            $content = fread($fp, filesize($filepath)); //二进制数据
            fclose($fp);
            return ['dataHandler'=>$content,'fileName'=>$file->filename,'type'=>$type];
        }else{
            return false;
        }
    }

    /**
     * 获取文件信息
     * @param $guid
     * @return array|bool
     */
    public static function _get_agent_file($guid)
    {
        $file = UploadFile::findOne(['guid'=>$guid]);
        if(null!==$file){
            $upload_dir = Yii::$app->params['upload']['attachment_root_dir'];
            $filepath   = $upload_dir . '/' . $file->filename;
            if(!file_exists($filepath)){
                return false;
            }
            if(@filesize($filepath)>2*1024*1024){
                $newname = str_replace($guid,$guid.'_new',$filepath);
                if(strtolower($file->filetype)=='jpg'){
                    $image = @imagecreatefromjpeg($filepath);
                    imagejpeg($image,$newname,1);
                }
                if(strtolower($file->filetype)=='png'){
                    $image = @imagecreatefrompng($filepath);
                    imagepng($image,$newname,1);
                }
                $filepath = $newname;
            }
            $fp = fopen($filepath, 'rb',0);
            $content = fread($fp, filesize($filepath)); //二进制数据
            fclose($fp);
            return ['dataHandler'=>$content,'fileInfo'=>$file->attributes];
        }else{
            return false;
        }
    }

    public static function encrypt($string)
    {
        //加密用的密钥文件
        $key = "ITM2015";
        //加密方法
        $cipher_alg = MCRYPT_TRIPLEDES;
        //初始化向量来增加安全性
        $iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher_alg,MCRYPT_MODE_ECB), MCRYPT_RAND);
        //开始加密
        $encrypted_string = mcrypt_encrypt($cipher_alg, $key, $string, MCRYPT_MODE_ECB, $iv);
        return base64_encode($encrypted_string);//转化成16进制
    }

    public static function decrypt($string)
    {
        $string = base64_decode($string);
        //解密用的密钥文件
        $key = "ITM2015";
        //解密方法
        $cipher_alg = MCRYPT_TRIPLEDES;
        //初始化向量来增加安全性
        $iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher_alg,MCRYPT_MODE_ECB), MCRYPT_RAND);
        //开始解密
        $decrypted_string = mcrypt_decrypt($cipher_alg, $key, $string, MCRYPT_MODE_ECB, $iv);
        return trim($decrypted_string);
    }

    /**
     * 删除空格
     * @param $str
     * @return mixed
     */
    public static function trimall($str)
    {
        $qian=array(" ","　","\t","\n","\r");
        $hou=array("","","","","");
        $str = str_replace($qian,$hou,$str);
        if(strpos($str, 'www.')!=false){
            $str = str_replace('www.','',$str);
        }
        return $str;
    }

    /**
    * 取汉字的第一个字的首字母
    * @param type $str
    * @return string|null
    */
   public static function _getFirstCharter($str){
        if(empty($str)){
            return '';
        }
        $fchar=ord($str{0});
        if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
        $s1=iconv('UTF-8','gb2312',$str);
        $s2=iconv('gb2312','UTF-8',$s1);
        $s=$s2==$str?$s1:$str;
        $asc=ord($s{0})*256+ord($s{1})-65536;
        if($asc>=-20319&&$asc<=-20284) return 'A';
        if($asc>=-20283&&$asc<=-19776) return 'B';
        if($asc>=-19775&&$asc<=-19219) return 'C';
        if($asc>=-19218&&$asc<=-18711) return 'D';
        if($asc>=-18710&&$asc<=-18527) return 'E';
        if($asc>=-18526&&$asc<=-18240) return 'F';
        if($asc>=-18239&&$asc<=-17923) return 'G';
        if($asc>=-17922&&$asc<=-17418) return 'H';
        if($asc>=-17417&&$asc<=-16475) return 'J';
        if($asc>=-16474&&$asc<=-16213) return 'K';
        if($asc>=-16212&&$asc<=-15641) return 'L';
        if($asc>=-15640&&$asc<=-15166) return 'M';
        if($asc>=-15165&&$asc<=-14923) return 'N';
        if($asc>=-14922&&$asc<=-14915) return 'O';
        if($asc>=-14914&&$asc<=-14631) return 'P';
        if($asc>=-14630&&$asc<=-14150) return 'Q';
        if($asc>=-14149&&$asc<=-14091) return 'R';
        if($asc>=-14090&&$asc<=-13319) return 'S';
        if($asc>=-13318&&$asc<=-12839) return 'T';
        if($asc>=-12838&&$asc<=-12557) return 'W';
        if($asc>=-12556&&$asc<=-11848) return 'X';
        if($asc>=-11847&&$asc<=-11056) return 'Y';
        if($asc>=-11055&&$asc<=-10247) return 'Z';
        return '0';
   }

    /**
     * 注册商epp_id
     * @param $company_id
     * @return string
     */
    public static function get_registrar_epp_id($company_id)
    {
        $agent = Registrar::findOne($company_id);
        if(null!==$agent && $agent->epp_id){
            return $agent->epp_id;
        }else{
            return false;
        }
    }

    /**
     *获取注册商epp_password
     * @param $company_id
     * @return bool|mixed|null|void
     */
    public static function get_registrar_epp_password($company_id)
    {
        $agent = Registrar::findOne($company_id);
        if(null!==$agent && $agent->epp_password){
            return $agent->epp_password;
        }else{
            return false;
        }
    }
    /**
     *获取注册商webserviceid
     * @param $company_id
     * @return bool|mixed|null|void
     */
    public static function get_registrar_webservice_id($company_id)
    {
        $agent = Registrar::findOne($company_id);
        if(null!==$agent && $agent->webservice_id){
            return $agent->webservice_id;
        }else{
            return false;
        }
    }

    /**
     *获取注册商sha1_password
     * @param $company_id
     * @return bool|mixed|null|void
     */
    public static function get_registrar_webservice_passord($company_id)
    {
        $agent = Registrar::findOne($company_id);
        if(null!==$agent && $agent->webservice_password){
          return $agent->password;
        }else{
            return false;
        }
    }
    /**
     * 获取所有zhuce商
     */
    public static function get_all_registrars()
    {
        $idsquery = new Query();
        $idsquery->select('id,company_name_zh_cn')
                    ->from('registrar')
                    ->where("status = '正常' and deleted = '否'")
                    ->orderBy('id desc');
        $agents =  $idsquery->createCommand()->queryAll();
        return $agents;
    }

    /**
     * 通过用户ID获取该用户可管理的注册商ID
     */
    public static function get_registrar_id_by_user_id($uid)
    {
        $list = $name = [];
        $userinfo = RegistryUser::findOne(['id'=>$uid]);
        if (empty($userinfo)) {
            return array(
                'a'=>$list,
                's'=>'-1',
                'name'=>$name
            );
        }
        $idsquery = new Query();
        $idsquery->select('registrar_id')
                    ->from('user_manage_scope')
                    ->where("user_id = {$uid}");
        $user_agent_ids = $idsquery->createCommand()->queryColumn();
        foreach($user_agent_ids as $registrar_id){
           $name[$registrar_id] = Service::get_company_name_by_id($registrar_id);
        }
        return array(
            'a'=>$user_agent_ids,
            's'=>!empty($user_agent_ids)?implode(',', $user_agent_ids):'-4',
            'name'=>$name
        );
    }

    /**
     * 通过用户ID获取该用户可管理的代理商ID
     */
    public static function get_agent_id_by_user_id($uid)
    {
        $list = $name = [];
        $userinfo = RegistryUser::findOne(['id'=>$uid]);
        if (empty($userinfo)) {
            return array(
                'a'=>$list,
                's'=>'-1',
                'name'=>$name
            );
        }
        $idsquery = new Query();
        $idsquery->select('agent_code')
            ->from('user_agent_scope')
            ->where("user_id = {$uid}");
        $user_agent_ids = $idsquery->createCommand()->queryColumn();
        foreach($user_agent_ids as $agent_code){
            $name[$agent_code] = Service::get_agent_name_by_code($agent_code);
        }
        return array(
            'a'=>$user_agent_ids,
            's'=>!empty($user_agent_ids)?implode('\',\'',array_unique($user_agent_ids)):'-4',
            'name'=>$name
        );
    }

    /**
     *获取代理商名称
     * @param $code
     * @return string
     */
    public static function get_agent_name_by_code($code){

        $agent = Agent::findOne(['agent_code'=>$code]);
        if(null!==$agent){
            return $agent->company_name;
        }else{
            return '--';
        }
    }
    /**
     * 获取文件的原名称
     * @param  [type] $guid [description]
     * @return [type]       [description]
     */
    public static function get_file_original_name($guid)
    {
        $uploadfile = UploadFile::findOne(['guid'=>$guid]);
        if(null!=$uploadfile){
            return $uploadfile->original_filename;
        }else{
            return '';
        }
    }
    /**
     * 获取注册商公司名称
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function getRegistrarName($id)
    {
        $str = '---';
        $info = Registrar::findOne($id);
        if (!empty($info)&&isset($info['company_name_zh_cn'])) {
            $str = $info['company_name_zh_cn'];
        }
        return $str;
    }
}