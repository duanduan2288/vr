<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015-9-1
 * Time: 17:33
 */

namespace app\controllers;
use app\models\AuditIssue;
use app\models\AuditReason;
use app\models\CustomerFeedback;
use app\models\Dictionary;
use app\models\HelpAudit;
use app\models\RegistryUser;
use yii;
use yii\web\Controller;
use \yii\db\Query;
use yii\data\Pagination;
use yii\db\Expression;
use yii\db\Exception;
use yii\helpers\HtmlPurifier;
use app\models\AuditData;
use itm\epp\EPP;
use app\models\Issue;
use itm\domain\RegAuthGreenChannel;
use app\models\EppOperationLog;
use app\models\Service;
use yii\helpers\Url;
use app\models\ServiceOperationLog;

class AuditDataController extends Controller{

    protected $auditstatus = ['待审核','审核中','审核退回'];

    protected $uid;

    public function init()
    {
        $uid = Yii::$app->user->id;

        if(!empty($uid)){
            $this->uid = $uid;
        }else{
            if(Yii::$app->request->isAjax){
                $this->error('请先登录');
            }else{
                return $this->redirect('/site/login');
            }
        }
    }

    public function actionIndex(){

        //判断用户是否可以初审
        $this->check_user_rights();

        $domain = HtmlPurifier::process(trim(Yii::$app->request->get('domain','')));
        $start_date = HtmlPurifier::process(trim(Yii::$app->request->get('start_date','')));
        $end_date = HtmlPurifier::process(trim(Yii::$app->request->get('end_date','')));
        $sort_start_date = HtmlPurifier::process(trim(Yii::$app->request->get('sort_start_date','desc')));
        $sort_created = HtmlPurifier::process(trim(Yii::$app->request->get('sort_created','desc')));
        $sort_status = HtmlPurifier::process(trim(Yii::$app->request->get('sort_status','desc')));
        //排序
        $sortarr = "created {$sort_created}, service_start_time {$sort_start_date} ,audit_result {$sort_status}";

        $auditdata = AuditData::find()
            ->where('audit_category="初审" and audit_result in ("待审核","审核中","审核退回")')
            ->andWhere("(now() < date_add(service_start_time,INTERVAL 10 DAY) and whois_update='是') or (service_start_time is null and whois_update='否')");

        //用户可以审核的注册商
        $registrars = Service::get_registrar_id_by_user_id($this->uid);
        $auditdata->andWhere("registrar_id in ( {$registrars['s']} )");

        if (!empty($domain))
        {
            $auditdata->andWhere("domain LIKE '%{$domain}%'");
        }
        if(!empty($start_date) && !empty($end_date))
        {
            $auditdata->andWhere("created >='{$start_date}' and created <='{$end_date} 23:59:59'");
        }

        $auditdata->orderBy($sortarr);
        $count = $auditdata->count();

        $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
        $auditdata->offset($pages->offset)->limit($pages->limit);

        $data = $auditdata->all();

        $data = $this->get_whois_info($data);

        $url = Url::to(['audit-data/index',
            'start_date' => $start_date,
            'end_date' => $end_date,
            'domain' =>$domain,
            'sort_start_date'=>$sort_start_date=='desc'?'asc':'desc',
            'sort_created'=>$sort_created=='desc'?'asc':'desc',
            'sort_status'=>$sort_status=='desc'?'asc':'desc',
        ]);
        return $this->render('index',[
            'start_date' => $start_date,
            'end_date' => $end_date,
            'domain' =>$domain,
            'data' => $data,
            'pages'=>$pages,
            'sort_start_date'=>$sort_start_date,
            'sort_created'=>$sort_created,
            'sort_status'=>$sort_status,
            'url'=>$url
        ]);
    }

    /**
     * 查询域名的注册人信息
     * @param $data
     */
    private function get_whois_info($data){
        $arr = [];
        $ignore_epps = ['jami','gdhy'];
        if(!empty($data)){
            $epp = new EPP();
            foreach($data as $auditdata){
                if('否'==$auditdata['whois_update']){
                    $info = $epp->whois($auditdata['domain']);

                    if (!empty($info) && isset($info['message']) && trim($info['message']) == 'No match') {
                        $auditdata->audit_result = '已删除';
                        $auditdata->whois_update = '是';
                        $auditdata->modified = new Expression('NOW()');
                        $auditdata->save();
                        continue;
                    }
                    if (!empty($info) && $info['code'] != 1) {
                       continue;
                    }
                    $domaininfo = explode("\r\n", $info['message']);
                    $organization_flag = HelpAudit::checkArray('Registrant Organization:', $domaininfo);//注册公司名称
                    $registrant_name_flag = HelpAudit::checkArray('Registrant Name:', $domaininfo);//注册人姓名
                    $domain_agent_tel_flag = HelpAudit::checkArray('Registrant Phone:', $domaininfo);//域名经办人电话
                    $domain_agent_tel_ext_flag = HelpAudit::checkArray('Registrant Phone Ext:', $domaininfo);//域名经办人分机号
                    $domain_agent_email_flag = HelpAudit::checkArray('Registrant Email:', $domaininfo);//域名经办人邮箱
                    $domain_agent_street_flag = HelpAudit::checkArray('Registrant Street:', $domaininfo);//域名经办人街道地址
                    $domain_agent_city_flag = HelpAudit::checkArray('Registrant City:', $domaininfo);//域名经办人城市
                    $domain_agent_state_flag = HelpAudit::checkArray('Registrant State/Province:', $domaininfo);//域名经办人省份
                    $domain_agent_country_flag = HelpAudit::checkArray('Registrant Country:', $domaininfo);//域名经办人国家
                    $creation_flag = HelpAudit::checkArray('Creation Date:', $domaininfo);//创建日期
                    $expiry_flag = HelpAudit::checkArray('Registry Expiry Date:', $domaininfo);//到期日期

                    if ($creation_flag['info'] != 'ok' && ($organization_flag['info'] != 'ok' || 'ok'!=$registrant_name_flag['info'])) {
                        continue;
                    }
                    //获取注册人名称
                    $name = HelpAudit::get_whois_param($domaininfo,'Registrant Name:',$registrant_name_flag['k']);
                    //获取注册人公司名称
                    $organization = HelpAudit::get_whois_param($domaininfo,'Registrant Organization:',$organization_flag['k']);
                    //获取注册人电话
                    $domain_agent_tel = HelpAudit::get_whois_param($domaininfo,'Registrant Phone:',$domain_agent_tel_flag['k']);
                    //获取注册人分机
                    $domain_agent_tel_ext = HelpAudit::get_whois_param($domaininfo,'Registrant Phone Ext:',$domain_agent_tel_ext_flag['k']);
                    //获取注册人邮箱
                    $domain_agent_email = HelpAudit::get_whois_param($domaininfo,'Registrant Email:',$domain_agent_email_flag['k']);
                    //获取注册人街道地址
                    $domain_agent_street = HelpAudit::get_whois_param($domaininfo,'Registrant Street:',$domain_agent_street_flag['k']);
                    //获取注册人城市
                    $domain_agent_city = HelpAudit::get_whois_param($domaininfo,'Registrant City:',$domain_agent_city_flag['k']);
                    //获取注册人省份
                    $domain_agent_state = HelpAudit::get_whois_param($domaininfo,'Registrant State/Province:',$domain_agent_state_flag['k']);
                    //获取注册人国家
                    $domain_agent_country = HelpAudit::get_whois_param($domaininfo,'Registrant Country:',$domain_agent_country_flag['k']);
                    //获取创建日期
                    $start_date = HelpAudit::get_whois_param($domaininfo,'Creation Date:',$creation_flag['k']);
                    //获取到期日期
                    $end_date = HelpAudit::get_whois_param($domaininfo,'Registry Expiry Date:',$expiry_flag['k']);
                    //注册年限
                    $period = date('Y',strtotime($end_date))-date('Y',strtotime($start_date));
                    //更新数据库
                    $start_date = date('Y-m-d H:i:d',strtotime($start_date));
                    if(!in_array($auditdata->registrar_epp_id,$ignore_epps)){
                        $country = isset(Dictionary::$countries[$domain_agent_country])?Dictionary::$countries[$domain_agent_country]:'';
                        $address = $country.' '.$domain_agent_state.' '.$domain_agent_city.' '.$domain_agent_street;
                        $auditdata->domain_agent = $name;
                        $auditdata->domain_agent_tel = empty($domain_agent_tel_ext)?$domain_agent_tel:$domain_agent_tel."-".$domain_agent_tel_ext;
                        $auditdata->domain_agent_email = $domain_agent_email;
                        $auditdata->domain_agent_address = $address;
                    }
                    $auditdata->service_start_time = $start_date;
                    $auditdata->registrant_name = $name;
                    $auditdata->registrant_organization = $organization;
                    $auditdata->registered_years = $period;
                    $auditdata->whois_update = '是';
                    $auditdata->modified = new Expression('NOW()');
                    $auditdata->save();
                    if(time()<strtotime('+10 days',strtotime($start_date))){
                        $arr[] = $auditdata;
                    }
                }else{
                    if(time()<strtotime('+10 days',strtotime($auditdata->service_start_time))){
                        $arr[] = $auditdata;
                    }
                }
            }
        }
        return $arr;

    }
    /**
     * 资料审核
     */
    public function actionAudit(){

        //判断用户是否可以初审
        $this->check_user_rights();

        $guid = HtmlPurifier::process(trim(Yii::$app->request->get('guid','')));
        if(empty($guid)){
            $this->error('请选择要审核的域名');
        }
        $auditdata = AuditData::findOne(['guid'=>$guid]);
        if(null===$auditdata){
            $this->error('您要审核的记录不存在');
        }
        //处于审核中的域名才能进行审核
        if('初审'!==$auditdata['audit_category'] || !in_array($auditdata['audit_result'],$this->auditstatus)){

            $this->error('您要审核的记录已不能审核');
        }
        if(!Service::get_remaining_days($auditdata['service_start_time']))
        {
            $this->error('您要审核的记录已过审核周期');
        }
        return $this->render('audit',
            [
                'auditdata'=>$auditdata
            ]);
    }

    /**
     * 初审审核拒绝
     */
    public function actionReject(){

        //判断用户是否可以初审
        $this->check_user_rights();

        $domain_data_id = HtmlPurifier::process(trim(Yii::$app->request->post('audit_data_id','')));
        if(empty($domain_data_id)){
            $this->error('请选择要审核的域名');
        }
        $auditdata = AuditData::findOne(['guid'=>$domain_data_id]);
        if(null==$auditdata){
            $this->error('您要审核的域名不存在');
        }
        if('初审'!==$auditdata['audit_category'] || !in_array($auditdata['audit_result'],$this->auditstatus)){

            $this->error('您要审核的记录已不能审核');
        }
        $reason_id = HtmlPurifier::process(trim(Yii::$app->request->post('reason_id','')));

        if(empty($reason_id)){
            $this->error('请选择拒绝原因');
        }
        $display_reason = HtmlPurifier::process(trim(Yii::$app->request->post('display_reason','')));

        $auditreason = AuditReason::findOne(['id'=>$reason_id,'deleted'=>'否']);
        if(null==$auditreason){
            $this->error('原因不存在，或已被删除');
        }
        $memo = HtmlPurifier::process(trim(Yii::$app->request->post('memo','')));
        $attachment = Yii::$app->request->post('attachment',[]);
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $issuemodel = new Issue();
            $issue = $issuemodel->getIssue($auditdata->issue_id);
            if(!$issue){
                throw new yii\base\Exception('工单不存在');
            }
            $issue->operator = $this->uid;
            $issue->audit_status = '初审审核拒绝';
            $issue->current_state = '已完成';
            $issue->memo = HtmlPurifier::process($memo);
            $issue->attachment = json_encode($attachment);
            $issue->reject_reason_id = $reason_id;
            $issue->display_reason = $display_reason;
            $issue->domain = $auditdata->domain;
            $res = $issue->save();
            if(!$res){
                throw new yii\base\Exception('审核记录保存失败');
            }
            $auditdata->reject_reason_id = $reason_id;
            $auditdata->display_reason = $display_reason;
            $auditdata->audit_result = '审核拒绝';
            $auditdata->operator_id = $this->uid;
            $auditdata->audit_user_id = $this->uid;
            $auditdata->audit_when = new Expression('NOW()');
            $auditdata->audit_time = new Expression('NOW()');
            $auditdata->modified = new Expression('NOW()');
            $flag = $auditdata->save();
            if(!$flag){
                throw new yii\base\Exception('审核记录保存失败');
            }
            //调用审核拒绝接口
            $username = Yii::$app->params['audit_admin']['username'];
            $pw = Yii::$app->params['audit_admin']['password'];

            $array = ['registrarId'=>$auditdata->registrar_epp_id,'contactId'=>$auditdata->contact_id,'domainName'=>$auditdata->domain];

            $regwebservice = new RegAuthGreenChannel($username,$pw);
            $returndata = $regwebservice->RegistrantChangeAuditReject($array);

            if(intval($returndata['code'])!==1){
                throw new yii\base\Exception($returndata['message']);
            }
            //记录日志
            EppOperationLog::insert($this->uid, 'registrationAuditReject', '202.173.9.4', '9944', json_encode($array, JSON_UNESCAPED_UNICODE), 1, json_encode($returndata, JSON_UNESCAPED_UNICODE));

            $transaction->commit();
            $this->success('域名审核拒绝');

        } catch(Exception $e) {
            $transaction->rollBack();
            $this->error($e->getMessage());
        }
    }
    /**
     * 初审审核退回
     */
    public function actionReturn(){
        //判断用户是否可以初审
        $this->check_user_rights();

        $domain_data_id = HtmlPurifier::process(trim(Yii::$app->request->post('audit_data_id','')));
        if(empty($domain_data_id)){
            $this->error('请选择要审核的域名');
        }
        $auditdata = AuditData::findOne(['guid'=>$domain_data_id]);
        if(null==$auditdata){
            $this->error('您要审核的域名不存在');
        }
        if('初审'!==$auditdata['audit_category'] || !in_array($auditdata['audit_result'],$this->auditstatus)){

            $this->error('您要审核的记录已不能审核');
        }
        $reason_id = HtmlPurifier::process(trim(Yii::$app->request->post('reason_id','')));

        if(empty($reason_id)){
            $this->error('请选择拒绝原因');
        }
        $display_reason = HtmlPurifier::process(trim(Yii::$app->request->post('display_reason','')));

        $auditreason = AuditReason::findOne(['id'=>$reason_id,'deleted'=>'否']);
        if(null==$auditreason){
            $this->error('原因不存在，或已被删除');
        }
        $before = $auditdata->attributes;

        $memo = HtmlPurifier::process(trim(Yii::$app->request->post('memo','')));
        $attachment = Yii::$app->request->post('attachment',[]);
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $issuemodel = new Issue();
            $issue = $issuemodel->getIssue($auditdata->issue_id);
            if(!$issue){
                throw new yii\base\Exception('工单不存在');
            }
            $issue->operator = $this->uid;
            $issue->audit_status = '初审审核退回';
            $issue->current_state = '处理中';
            $issue->memo = HtmlPurifier::process($memo);
            $issue->attachment = json_encode($attachment);
            $issue->reject_reason_id = $reason_id;
            $issue->display_reason = $display_reason;
            $issue->domain = $auditdata->domain;
            $res = $issue->save();
            if(!$res){
                throw new yii\base\Exception('审核记录保存失败');
            }
            $auditdata->reject_reason_id = $reason_id;
            $auditdata->display_reason = $display_reason;
            $auditdata->audit_result = '审核退回';
            $auditdata->operator_id = $this->uid;
            $auditdata->audit_user_id = $this->uid;
            $auditdata->audit_time = new Expression('NOW()');
            $auditdata->modified = new Expression('NOW()');
            $flag = $auditdata->save();
            if(!$flag){
                throw new yii\base\Exception('审核记录保存失败');
            }
            //记录操作日志
            ServiceOperationLog::create_operation_log(json_encode($auditdata->attributes),json_encode($before),
                '域名初审退回','/audit-data/return',$this->uid);

            $transaction->commit();
            $this->success('域名审核退回');

        } catch(Exception $e) {
            $transaction->rollBack();
            $this->error($e->getMessage());
        }
    }
    /**
     * 审核通过
     */
    public function actionPass(){

        //判断用户是否可以初审
        $this->check_user_rights();
        $domain_data_id = HtmlPurifier::process(trim(Yii::$app->request->post('audit_data_id','')));
        if(empty($domain_data_id)){
            $this->error('请选择要审核的域名');
        }
        $auditdata = AuditData::findOne(['guid'=>$domain_data_id]);
        if(null==$auditdata){
            $this->error('您要审核的域名不存在');
        }
        if('初审'!==$auditdata['audit_category'] || !in_array($auditdata['audit_result'],$this->auditstatus)){

            $this->error('您要审核的记录已不能审核');
        }
        $before = $auditdata->attributes;

        $memo = HtmlPurifier::process(trim(Yii::$app->request->post('memo','')));
        $attachment = Yii::$app->request->post('attachment',[]);
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $issuemodel = new Issue();
            $issue = $issuemodel->getIssue($auditdata->issue_id);
            if(!$issue){
                throw new yii\base\Exception('工单不存在');
            }
            $issue->operator = $this->uid;
            $issue->audit_status = '初审审核通过';
            $issue->current_state = '处理中';
            $issue->memo = HtmlPurifier::process($memo);
            $issue->attachment = json_encode($attachment);
            $issue->domain = $auditdata->domain;
            $res = $issue->save();
            if(!$res){
                throw new yii\base\Exception('审核记录保存失败');
            }
            $auditdata->audit_result = '审核通过';
            $auditdata->operator_id = $this->uid;
            $auditdata->audit_user_id = $this->uid;
            $auditdata->audit_time = new Expression('NOW()');
            $auditdata->modified = new Expression('NOW()');
            $flag = $auditdata->save();
            if(!$flag){
                throw new yii\base\Exception('审核记录保存失败');
            }
            //将终端客户回访表中注册域名数量加1,如果不存在则添加
            $customer = CustomerFeedback::findOne(['registrant_name'=>$auditdata->registrant_name]);
            if(null==$customer){
                $customer = new CustomerFeedback();
                $customer->guid = Service::create_guid();
                $customer->registrant_name = $auditdata->registrant_name;
                $customer->registrant_organization = $auditdata->registrant_organization;
                $customer->contact_id = $auditdata->contact_id;
                $customer->telephone = $auditdata->domain_agent_tel;
                $customer->mobile = $auditdata->domain_agent_mobile;
                $customer->email = $auditdata->domain_agent_email;
                $customer->email = $auditdata->domain_agent_email;
                $customer->feedback_status = $auditdata->feedback_status;
                $customer->domain_count = 1;
                $customer->created = new Expression('NOW()');
                $customer->registrar_id = $auditdata->registrar_id;
            }else{
                $customer->domain_count = $customer->domain_count + 1;
            }
            $customer->operator_id = $this->uid;
            $customer->contact_update_flag = '是';
            $customer->modified =  new Expression('NOW()');
            if(!$customer->save()){
                throw new yii\base\Exception(json_encode($customer->errors,JSON_UNESCAPED_UNICODE));
            }
            //记录操作日志
            ServiceOperationLog::create_operation_log(json_encode($auditdata->attributes),json_encode($before),
                '域名初审通过','/audit-data/pass',$this->uid);

            $transaction->commit();
            $this->success('域名审核通过');
        } catch(Exception $e) {
            $transaction->rollBack();
            $this->error($e->getMessage());
        }
    }

    /**
     * 取消标记
     */
    public function actionCancelFlag(){
        //判断用户是否可以初审
        $this->check_user_rights();
        $guid = HtmlPurifier::process(trim(Yii::$app->request->post('guid','')));
        if(empty($guid)){
            $this->error('参数错误');
        }
        $auditdata = AuditData::findOne(['guid'=>$guid]);
        if(null===$auditdata){
            $this->error('数据不存在');
        }
        if('否'==$auditdata->update_flag){

            $this->error('无需再次取消');
        }
        $before = $auditdata->attributes;

        $auditdata->update_flag = '否';
        $auditdata->modified = new Expression('NOW()');
        $auditdata->operator_id = $this->uid;
        if($auditdata->save())
        {
            //记录操作日志
            ServiceOperationLog::create_operation_log(json_encode($auditdata->attributes),json_encode($before),
                '取消标记','/audit-data/cancel-flag',$this->uid);

            $this->success('取消成功');
        }
        else
        {
            $this->error('取消失败');
        }
    }

    /**
     * 补全记录
     * @return string
     */
    public function actionBackTracking(){
        //判断用户是否可以初审
        $this->check_user_rights();

        $id = HtmlPurifier::process(trim(Yii::$app->request->get('guid','')));

        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('guid','');
        }
        if(empty($id)){
            $this->error('参数错误');
        }
        $auditdata = AuditData::findOne(['guid'=>$id]);
        if(null==$auditdata){
            $this->error('记录不存在');
        }
        $before = $auditdata->attributes;

        if(Yii::$app->request->isAjax){
            $parseUrl = HtmlPurifier::process(trim(Yii::$app->request->post('parse_url','')));
            $parseUrl = empty($parseUrl)?$auditdata->parse_url:$parseUrl;

            $trademark_reg_no = HtmlPurifier::process(trim(Yii::$app->request->post('trademark_reg_no','')));
            $trademark_reg_no = empty($trademark_reg_no)?$auditdata->trademark_reg_no:$trademark_reg_no;

            $tm_class_type = HtmlPurifier::process(trim(Yii::$app->request->post('tm_class_type','')));
            $tm_class_type = empty($tm_class_type)?$auditdata->tm_class_type:$tm_class_type;

            $tm_issuing_country = HtmlPurifier::process(trim(Yii::$app->request->post('tm_issuing_country','')));
            $tm_issuing_country = empty($tm_issuing_country)?$auditdata->tm_issuing_country:$tm_issuing_country;

            $tm_expires_date = HtmlPurifier::process(trim(Yii::$app->request->post('tm_expires_date','')));
            $tm_expires_date = empty($tm_expires_date)?$auditdata->tm_expires_date:$tm_expires_date;

            $auditdata->parse_url = $parseUrl;
            $auditdata->trademark_reg_no = $trademark_reg_no;
            $auditdata->tm_class_type = $tm_class_type;
            $auditdata->tm_issuing_country = $tm_issuing_country;
            $auditdata->operator_id = $this->uid;
            $auditdata->tm_expires_date = $tm_expires_date;
            $auditdata->modified = new Expression('NOW()');
            if(!$auditdata->save()){
                $this->error('补录失败');
            }else{
                //记录操作日志
                ServiceOperationLog::create_operation_log(json_encode($auditdata->attributes),json_encode($before),
                    '补全信息','/audit-data/back-tracking',$this->uid);

                $this->success('补录成功');
            }
        }
        return $this->render('backtracking',['model'=>$auditdata]);
    }

    /**
     * 判断用户是否可以进行初审
     */
    private function check_user_rights(){
        //判断用户是否可以审核
        $userinfo = RegistryUser::findOne($this->uid);
        $audit_scope = empty($userinfo->audit_scope)?[]:json_decode($userinfo->audit_scope);
        if(empty($audit_scope) || !in_array('初审',$audit_scope)){
            $this->error('您没有初审权限');
        }
    }
}