<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015-8-27
 * Time: 13:15
 */
namespace app\controllers;
use yii;
use yii\web\Controller;
use app\extensions\epp\EPP;
use app\models\Service;
use app\extensions\UcloudSms;
use yii\validators\EmailValidator;
use yii\db\Query;
use yii\data\Pagination;
use app\models\MessageLog;
use app\models\EmailLog;

class SendMessageController extends Controller{

    public function actionIndex(){
        $uid = Yii::$app->user->id;
        if(empty($uid)){
           return $this->redirect('/site/login');
        }
        return $this->render('index');
    }

    /**
     * 发送邮件、短信
     */
    public function actionSend(){


        $uid = Yii::$app->user->id;
        if(empty($uid)){
            $this->error('请先登录');
        }

        $domain = Yii::app()->request->getParam('domain','');
        if(empty($domain)){
            $this->error('请输入域名');
        }
        if(strpos($domain,'.商标')==false){
            $domain = $domain.'.商标';
        }
        //查询whois查询域名的到期日期
        $epp = new EPP();
        $returndata = $epp->whois($domain);

        if (!empty($returndata) && isset($returndata['message']) && trim($returndata['message']) == 'No match') {
            $this->error('查询域名信息失败');
        }

        if (!empty($returndata) && $returndata['code'] != 1) {
            $this->error('查询域名信息失败');
        }

        $domaininfo = explode("\r\n", $returndata['message']);

        $iana_id_flag = $this->checkArray('Sponsoring Registrar IANA ID:', $domaininfo);//IANAid
        $phone_flag = $this->checkArray('Registrant Phone:', $domaininfo);//联系人电话号码
        $email_flag = $this->checkArray('Registrant Email:', $domaininfo);//邮箱
        $name_flag = $this->checkArray('Registrant Name:', $domaininfo);//注册人姓名
        $organization_flag = $this->checkArray('Registrant Organization:', $domaininfo);//注册公司名称
        $registrant_flag = $this->checkArray('Registrant ID:', $domaininfo);//注册人id
        $creation_flag = $this->checkArray('Creation Date:', $domaininfo);//创建日期
        $expiry_flag = $this->checkArray('Registry Expiry Date:', $domaininfo);//到期日期

        if ($iana_id_flag['info'] != 'ok' && ($name_flag['info'] != 'ok' || 'ok'!=$organization_flag['info'])) {
            $this->error('查询域名信息失败');
        }
        //需要去查询代理商数据库
        $ourianas = ['1481','1925'];

        //IANA ID
        $iana_id = $this->get_param($domaininfo,'Sponsoring Registrar IANA ID:',$iana_id_flag['k']);
        //获取电话号码
        $phone = $this->get_param($domaininfo,'Registrant Phone:',$phone_flag['k']);
        //获取邮箱
        $email = $this->get_param($domaininfo,'Registrant Email:',$email_flag['k']);
        //获取注册人名称
        $name = $this->get_param($domaininfo,'Registrant Name:',$name_flag['k']);
        //获取注册人公司名称
        $organization = $this->get_param($domaininfo,'Registrant Organization:',$organization_flag['k']);
        //客户信息
        $customer = empty($organization)?$name:$organization;

        //获取注册人ID
        $registrant_id = $this->get_param($domaininfo,'Registrant ID:',$registrant_flag['k']);
        //获取创建日期
        $start_date = $this->get_param($domaininfo,'Creation Date:',$creation_flag['k']);

        //获取到期日期
        $end_date = $this->get_param($domaininfo,'Registry Expiry Date:',$expiry_flag['k']);

        $period = date('Y',strtotime($end_date))-date('Y',strtotime($start_date));

        $end_date = date('Y年m月d日',strtotime($end_date));
        $start_date = date('Y年m月d日',strtotime($start_date));

        if(empty($email) && empty($phone)){
            $this->error('邮箱、手机均为空，无法发送');
        }

        $subject = '“'.$domain.'”域名注册成功通知';
        $send = false;
        //发送邮件
        $emailValidator = new EmailValidator();
        if(!empty($email) && $emailValidator->validate($email)){
            $content = $this->get_content($start_date,$end_date,$customer,$domain,$period,'email');
            $send = Service::sendMail([$email],$subject,$content,$domain,$uid);
        }
        $sms = false;
        $pos = strpos($phone,'.');
        $phone = substr($phone,$pos+1);
        //发送短信
        if(!empty($phone) && preg_match("/^1[0-9]{10}$/",$phone)) {
            $content = $this->get_content($start_date,$end_date,$customer,$domain,$period,'phone');
            $sms = UcloudSms::sendSms($phone, $content, $domain, $uid);
        }

        if(true!==$send){
            $this->error('邮件发送失败');
        }
        if(true!==$sms){
            $this->error('短信发送失败');
        }
        $this->success('发送成功');
    }

    public function actionEmailList(){
        $uid = Yii::$app->user->id;
        if (!empty($uid)){
            $query = new Query();
            $domain = (isset($_GET['domain']))?$_GET['domain']:'';
            $query->select('*')->from('email_log')->where('1=1');
            if (!empty($domain)) {
                $query->andWhere("domain = '{$domain}'");
            }

            $query->orderBy("id desc");
            $count = $query->count();
            $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
            $query->offset($pages->offset)->limit($pages->limit);
            $agents = $query->all();
            return $this->render('emailList',array(
                'lists' => $agents,
                'pages'=>$pages,
                'domain'=>$domain,
            ));
        }else{
            return $this->redirect('/site/login');
        }
    }
    public function actionMessageList(){
        $uid = Yii::$app->user->id;
        if (!empty($uid)){
            $query = new Query();
            $query->select('*')->from('message_log')->where('1=1');
            $domain = (isset($_GET['domain']))?$_GET['domain']:'';
            if (!empty($domain)) {
                $query->andWhere("domain = '{$domain}'");
            }

            $query->orderBy("id desc");
            $count = $query->count();
            $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
            $query->offset($pages->offset)->limit($pages->limit);
            $agents = $query->all();
            return $this->render('messageList',array(
                'lists' => $agents,
                'pages'=>$pages,
                'domain'=>$domain,
            ));
        }else{
            return $this->redirect('/site/login');
        }
    }

    /**
     * 重新发送邮件
     */
    public function actionResendMessage(){
        $uid = Yii::$app->user->id;
        if (!empty($uid)){
            $id = Yii::app()->request->getParam('id','');
            if(empty($id)){
                $this->error('参数错误');
            }
            $messagelog = MessageLog::findOne($id);
            if(null==$messagelog){
                $this->error('记录不存在');
            }
            $phone = json_decode($messagelog['to'],true);
            $sms = UcloudSms::sendSms($phone, $messagelog['content'], $messagelog['domain'], $uid);
            if(true!==$sms){
                $this->error('短信发送失败');
            }
            $this->success('发送成功');
        }else{
            $this->error('请先登录');
        }
    }

    /**
     * 发送邮件
     */
    public function actionResendEmail(){
        $uid = Yii::$app->user->id;
        if (!empty($uid)){
            $id = Yii::app()->request->getParam('id','');
            if(empty($id)){
                $this->error('参数错误');
            }
            $emaillog = EmailLog::findOne($id);
            if(null==$emaillog){
                $this->error('记录不存在');
            }
            $subject = '“'.$emaillog['domain'].'”域名注册成功通知';
            $email = json_decode($emaillog['to'],true);
            //发送邮件
            $send = Service::sendMail($email,$subject,$emaillog['body'],$emaillog['domain'],$uid);
            if(true!==$send){
                $this->error('邮件发送失败');
            }
            $this->success('发送成功');
        }else{
            $this->error('请先登录');
        }
    }
    /**
     * 获取邮件内容
     * @param $start_date
     * @param $end_date
     * @param $name
     * @param $domain
     * @param $period
     * @param $type
     * @return string
     */
    private function get_content($start_date,$end_date,$name,$domain,$period,$type){
        if('email'==$type){
            $controller = yii::$app->getController();
            $view_file = Service::get_mail_view('domain');
            $content = $controller->renderFile($view_file, ['domain'=>$domain,'registrant_name'=>$name, 'start_date'=>$start_date,'end_date'=>$end_date,'period'=>$period], true);
        }else{
            $content = '温馨提示：您的“'.$domain.'”域名已注册成功，注册年限'.$period.'年，自'.$start_date.'起开始生效。请点击：注册局.商标或www.internettrademark.com或致电：400-628-1121【“.商标”域名注册局】';
        }
        return $content;
    }
    /**
     * 获取处理好的参数
     * @param $domaininfo
     * @param $replace_str
     * @param $str
     * @return mixed|string
     */
    private function get_param($domaininfo,$replace_str,$str){
        $registrant = '';
        $str = trim($str);
        if (isset($domaininfo[$str])) {
            $registrant = str_replace($replace_str,'',$domaininfo[$str]);
        }
        return trim($registrant);
    }

    /**
     * 处理whois信息
     * @param $need
     * @param $arr
     * @return array
     */
    private function checkArray($need,$arr)
    {
        $return = array('info'=>'error','k'=>'');
        foreach( $arr as $k=>$v ){
            if ( strpos( $v , $need ) !== false )
            {
                $return = array('info'=>'ok','k'=>$k);break;
            }
        }
        return $return;
    }

}