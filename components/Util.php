<?php
namespace app\components;

class Util
{

    //截取特定字符串中间字符
    public static function intercept_str($sp,$str){
        $strarr=explode($sp,$str);
        return isset($strarr[1])?$strarr[1]:'';
    }
    /**
     * weibo  短链
     */
    public static function short_url($url)
    {
        $token = '2.00eZ2gPEBqdunDd0131316e5I8faWB';

        $url == trim($url);
        if (!preg_match('/^(http|https):\/\/.*/', $url)) {
            $url = 'http://'.$url;
        }

        $url = urlencode($url);

        $get = "https://api.weibo.com/2/short_url/shorten.json?access_token={$token}&url_long={$url}";

        $result = json_decode(file_get_contents($get), true);

        if (isset($result['urls'][0]['url_short'])) {
            return $result['urls'][0]['url_short'];
        }

        return '';
    }

    /**
     * 取ip 
     */
    public static function get_ip()
    {
        $ip = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
          $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
           $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
           $ip = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'-1';
        }
        return $ip;
    }
    
    /**
     * 用4位字符加密码密码，返回32位加密码过的密码
     */
    static function hash_password($password, $hash)
    {
        return  base64_encode( sha1( $password.$hash, true ).$hash);
    }

    /**
     * 取随机字符
     */
    static function random_hash($length = 4)
    {
        $salt = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
        $count = count($salt);
        $hash = '';
        for ($i = 0; $i < $length; $i++) {
            $hash .= $salt[mt_rand(0, $count-1)];
        }
        return $hash;
    }

     /**
     * 检测输入中是否含有错误字符
     *
     * @param char $string 要检查的字符串名称
     * @return TRUE or FALSE
     */
    public static function is_badword($string) {
    	$badwords = array("\\",'&',' ',"'",'"','/','*',',','<','>',"\r","\t","\n","#");
    	foreach($badwords as $value){
    		if(strpos($string, $value) !== FALSE) {
    			return TRUE;
    		}
    	}
    	return FALSE;
    }
    
    /**
     * 检查用户名是否符合规定
     *
     * @param STRING $username 要检查的用户名
     * @return 	TRUE or FALSE
     */
    public static function is_username($username) {
    	$strlen = strlen($username);
    	if(Util::is_badword($username) || !preg_match("/^[a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]+$/", $username)){
    		return false;
    	}
    	return true;
    }



    /**
     * 字符长度    中文一个长度，英文数字 半个 
     */
    public static function str_len($str)
    {
        $i = 0;
        $n = 0;
        while ($i <= strlen($str)) {
            $temp_str = substr($str, $i, 1);
            $ascnum = ord($temp_str);//得到字符串中第$i位字符的ascii码
    
            //如果ASCII位高与224，
            if ($ascnum >= 224) {
                $i += 3; //实际Byte计为3
                $n++; //字串长度计1
    
             //如果ASCII位高与192，
            } elseif ($ascnum >= 192) {
                $i += 2; //实际Byte计为2
                $n++; //字串长度计1
    
            //如果是大写字母，
            } elseif ($ascnum >= 65 && $ascnum <= 90) {
                $i++; //实际的Byte数仍计1个
                $n++; //但考虑整体美观，大写字母计成一个高位字符
    
            //其他情况下，包括小写字母和半角标点符号，
            } else {
                $i++; //实际的Byte数计1个
                $n += 0.5; //小写字母和半角标点等与半个高位字符宽...
            }
        }

        return intval($n);
    }


    /**
     * 截取字符
     */
    public static function cut_str($str, $len=9, $fix = '...')
    {
        if (strlen($str) <= $len) {
            return $str;
        }
    
        $return_str = '';
        $i = 0;
        $n = 0;
        while (($n < $len) && ($i <= strlen($str))) {
            $temp_str = substr($str, $i, 1);
            $ascnum = ord($temp_str);//得到字符串中第$i位字符的ascii码
    
            //如果ASCII位高与224，
            if ($ascnum >= 224) {
                $return_str .= substr($str, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
                $i += 3; //实际Byte计为3
                $n++; //字串长度计1
    
             //如果ASCII位高与192，
            } elseif ($ascnum >= 192) {
                $return_str .= substr($str, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
                $i += 2; //实际Byte计为2
                $n++; //字串长度计1
    
            //如果是大写字母，
            } elseif ($ascnum >= 65 && $ascnum <= 90) {
                $return_str .= substr($str, $i, 1);
                $i++; //实际的Byte数仍计1个
                $n++; //但考虑整体美观，大写字母计成一个高位字符
    
            //其他情况下，包括小写字母和半角标点符号，
            } else {
                $return_str .= substr($str, $i, 1);
                $i++; //实际的Byte数计1个
                $n += 0.5; //小写字母和半角标点等与半个高位字符宽...
            }
        }

    
        
        if (strlen($return_str) != strlen($str)) {
            $return_str .= $fix;
        }
 
    
        return $return_str;
    }

    /**
     * 发布时间显示
     */
    public static function publish_time($time)
    {
        if (strlen($time) == 13) {
          $time = intval(substr($time, 0, -3));
        }
        $now = time();
        $step = $now - $time;
        if ($step < 10) {
            return '刚刚';
        }

        if ($step < 60) {
            return $step . '秒前';
        }

        if ($step < 3600) {
            return intval($step/60) . '分钟前';
        }

        if (date('Y-m-d', $now) == date('Y-m-d', $time)) {
            return '今天 '.date('H:i', $time);
        }

        return date('m月d日 H:i', $time);
    }


    /**
     * 格式化微秒时间
     */
    public static function microdate($format, $microtime)
    {
        $time = intval(substr($microtime, 0, -3));
        return date($format, $time);

    }

    /**
     * 异步调用
     * @param unknown $url
     * @param unknown $post_data
     * @param unknown $cookie
     * @return boolean
     */
   public static  function triggerRequest($url, $post_data = array(), $cookie = array()){
        $method = "POST";  //可以通过POST或者GET传递一些参数给要触发的脚本
        $url_array = parse_url($url); //获取URL信息，以便平凑HTTP HEADER
        $port = isset($url_array['port'])? $url_array['port'] : 80;

        $fp = fsockopen($url_array['host'], $port, $errno, $errstr, 30);
        if (!$fp){
            return FALSE;
        }
        $getPath = $url_array['path'] ."?". $url_array['query'];
        if(!empty($post_data)) {
            $method = "POST";
        }
        $header = $method . " " . $getPath;
        $header .= " HTTP/1.1\r\n";
        $header .= "Host: ". $url_array['host'] . "\r\n "; //HTTP 1.1 Host域不能省略
        /**//*以下头信息域可以省略
         $header .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13 \r\n";
        $header .= "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,q=0.5 \r\n";
        $header .= "Accept-Language: en-us,en;q=0.5 ";
        $header .= "Accept-Encoding: gzip,deflate\r\n";
        */

        $header .= "Connection:Close\r\n";
        if(!empty($cookie)){
            $_cookie = strval(NULL);
            foreach($cookie as $k => $v) {
                $_cookie .= $k."=".$v."; ";
            }
            $cookie_str =  "Cookie: " . base64_encode($_cookie) ." \r\n";//传递Cookie
            $header .= $cookie_str;
        }
        if(!empty($post_data)){
            $_post = strval(NULL);
            foreach($post_data as $k => $v) {
                $_post .= $k."=".$v."&";
            }
            $post_str  = "Content-Type: application/x-www-form-urlencoded\r\n";//POST数据
            $post_str .= "Content-Length: ". strlen($_post) ." \r\n";//POST数据的长度
            $post_str .= $_post."\r\n\r\n "; //传递POST数据
            $header .= $post_str;
        }
        fwrite($fp, $header);
        //echo fread($fp, 1024); //我们不关心服务器返回
        fclose($fp);
        return true;
    }

    /**
     * 取图片路径
     * @param unknown $path
     * @param string $prefix
     * @param string $res_name
     * @return string
     */
    public static function image($path, $prefix = '')
    {
        $path_info = pathinfo($path);
        $file_path = '';

        if (!empty($prefix)) {
            $file_path = $path_info['dirname'].'/'.$prefix.'_'.$path_info['basename'];
        } else {
            $file_path = $path;
        }

        $full_path = ROOT_PATH.'/data/uploads/'.trim($file_path, '/');

        if (!file_exists($full_path)) {
            return '';
        }

        return 'http://'.$_SERVER['SERVER_NAME'].'/data/uploads/'.trim($file_path, '/');;
    }

    /**
    *身份证信息验证，测试版
    **/
    public static function CheckIdCard($vString)
    {
        // var_dump($vString);exit();

        $len = strlen($vString);
        if ($len != 18) {
            return false;
        }

        if($vString == ''){
            return false;
        }else{
            // var_dump((int)$vString[3]);exit();
            $s_weight = array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
            $s = array();
            for($i=0; $i<17; $i++){
                $s[$i] = (int)($vString[$i]) * $s_weight[$i];
            }
            $s_sum = array_sum($s);
            $s_flag = $s_sum%11;
            $v_arr = array('1','0','X','9','8','7','6','5','4','3','2');
            if($v_arr[$s_flag] == (string)$vString[17]){
                // echo 1;exit();
                return true;
            }else{
                // echo 2;exit();
                return false;
            }
        }
    }
    /**
    *原型打印
    **/
    public static function dump($a)
    {
        echo '<pre>';
        var_dump($a);
        echo '</pre>';
        exit();
    }

    /**
    *时间轴时间换算
    *@param string $add_time
    *@return string
    **/
    // public static function getTime($add_time)
    // {
    //     $timeSmtp = strtotime($add_time);
    //     $timeNow =
    // }


    /**
     * 重处理时间格式
     * @param unknown $time
     * @param string $format
     */
    function time_format($time, $format="Y-m-d H:i")
    {
        return date_format(date_create($time), $format);
        //return date($format, strtotime($time));
    }

    /**
     * 显示距离当前时间的字符串
     * @param $time int 时间戳
     * @return string
     * @author gaojj@alltosun.com
     */
    function time_past($time)
    {
        $now        = time();
        $time_past  = $now - strtotime($time);

        // 如果小于1分钟（60s），则显示"刚刚"
        if ($time_past < 60) {
            return '刚刚';
        }

        $time_mapping = array(
                '分钟' => '60',
                '小时' => '24',
                '天'   => '7',
                '周'   => '4',
                '月'   => '12',
                '年'   => '100'
        );

        $time_past = floor($time_past/60);

        foreach($time_mapping as $k=>$v) {
            if ($time_past < $v) return floor($time_past).$k.'前';
            $time_past = $time_past/$v;
        }

        // 如果小于1小时（60*60s），则显示N分钟前
        // 如果小于24个小时（60*60*24s），则显示N小时前
        // 如果大于24个小时（60*60*24s），则显示N天前
    }

    /**
     * 显示动态时间
     */
    public static function time_format_feed($time_str)
    {
        $now = time();
        $now_days = date('z', $now);
        $time = strtotime($time_str);
        $time_days = date('z', $time);

        $date_post = $now_days - $time_days;
        // 当天显示
        if ($date_post == 0) {
            $time_post = $now - $time;
            if ($time_post < 60) {
                return '刚刚';
            } else if ($time_post < 3600) {
                return floor($time_post/60) . '分钟前';
            } else if ($time_post < 21600) {
                return floor($time_post/3600) . '小时前';
            }

            return '今天'.date('H:i', $time);
        }
        if( $date_post < 3) {
            // 三天前
            $day_map = array('1'=>'昨天', '2'=>'前天');
            return  $day_map[$date_post]. ' '.date('H:i', $time);
        }

        return date('m月d日 H:i', $time);
    }

    /**
    * 将一个字串中含有全角的数字字符、字母、空格或'%+-()'字符转换为相应半角字符
    * @access public
    * @param string $str 待转换字串
    * @return string $str 处理后字串
    */
    public static function make_semiangle($str)
    {
        $arr = array( '０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
                    '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
                    'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
                    'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
                    'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
                    'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
                    'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
                    'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
                    'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
                    'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
                    'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
                    'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
                    'ｙ' => 'y', 'ｚ' => 'z',
                    '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[',
                    '】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']',
                    '‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<',
                    '》' => '>',
                    '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
                    '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',
                    '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
                    '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"',
                    '　' => ' ');
        $str = strtr($str, $arr);
        $str = str_replace(' ', '', $str);
        return trim($str);
    }

}
?>
