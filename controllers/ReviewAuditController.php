<?php
/**
 * Created by duan.
 * User: duan
 * Date: 2015-9-14
 * Time: 09:55
 */

namespace app\controllers;
use app\extensions\UcloudSms;
use app\models\AuditIssue;
use app\models\AuditReason;
use app\models\HelpAudit;
use yii;
use yii\web\Controller;
use \yii\db\Query;
use yii\data\Pagination;
use yii\db\Expression;
use yii\base\Exception;
use yii\helpers\HtmlPurifier;
use app\models\AuditData;
use app\models\RegistryUser;
use app\models\Issue;
use itm\domain\RegAuthGreenChannel;
use app\models\EppOperationLog;
use app\models\Service;
use yii\helpers\Url;
use app\models\ServiceOperationLog;

class ReviewAuditController extends Controller{

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
    /**
     * 复审列表
     * @return string
     */
    public function actionIndex(){
        //判断用户是否可以复审
        $this->check_user_rights();

        $domain = HtmlPurifier::process(trim(Yii::$app->request->get('domain','')));
        $start_date = HtmlPurifier::process(trim(Yii::$app->request->get('start_date',date('Y-m-d',strtotime('-1 day')))));
        $end_date = HtmlPurifier::process(trim(Yii::$app->request->get('end_date',date('Y-m-d'))));
        $sort_start_date = HtmlPurifier::process(trim(Yii::$app->request->get('sort_start_date','desc')));
        $sort_created = HtmlPurifier::process(trim(Yii::$app->request->get('sort_created','desc')));
        $sort_status = HtmlPurifier::process(trim(Yii::$app->request->get('sort_status','desc')));
        //排序
        $sortarr = "created {$sort_created}, service_start_time {$sort_start_date} ,audit_result {$sort_status}";

        $querylist = new Query();
        $querylist->select('*')->from('audit_data')
            ->where('audit_category="初审" and audit_result="审核通过"')
            ->andWhere("now() < date_add(service_start_time,INTERVAL 10 DAY)");

        //用户可以审核的注册商
        $registrars = Service::get_registrar_id_by_user_id($this->uid);
        $querylist->andWhere("registrar_id in ( {$registrars['s']} )");

        if (!empty($domain))
        {
            $querylist->andWhere("domain LIKE '%{$domain}%'");
        }
        if(!empty($start_date) && !empty($end_date))
        {
            $querylist->andWhere("created >='{$start_date}' and created <='{$end_date} 23:59:59'");
        }

        $querylist->orderBy($sortarr);
        $count = $querylist->count();

        $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
        $querylist->offset($pages->offset)->limit($pages->limit);

        $data = $querylist->all();
        //排序的url
        $url = Url::to(['review-audit/index',
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
     * 域名审核
     * @return string
     */
    public function actionAudit(){

        //判断用户是否可以复审
        $this->check_user_rights();

        $guid = HtmlPurifier::process(trim(Yii::$app->request->get('guid','')));
        if(empty($guid)){
            $this->error('请选择要审核的域名');
        }
        $auditdata = AuditData::findOne(['guid'=>$guid]);
        if(null===$auditdata){
            $this->error('您要审核的记录不存在');
        }
        //处于初审通过的域名才能进行审核
        if(!('初审'==$auditdata['audit_category'] && '审核通过'==$auditdata['audit_result'])){

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
    /*
     *域名审核拒绝
     *
     */
    public function actionReject(){

        //判断用户是否可以复审
        $this->check_user_rights();

        $domain_data_id = HtmlPurifier::process(trim(Yii::$app->request->post('audit_data_id','')));
        if(empty($domain_data_id)){
            $this->error('请选择要审核的域名');
        }
        $auditdata = AuditData::findOne(['guid'=>$domain_data_id]);
        if(null==$auditdata){
            $this->error('您要审核的域名不存在');
        }
        if(!('初审'==$auditdata['audit_category'] && '审核通过'==$auditdata['audit_result'])){

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
        $before = json_encode($auditdata->attributes);

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
            $issue->audit_status = '复审审核拒绝';
            $issue->current_state = '已完成';
            $issue->memo = $memo;
            $issue->attachment = json_encode($attachment);
            $issue->reject_reason_id = $reason_id;
            $issue->display_reason = $display_reason;
            $issue->domain = $auditdata->domain;
            $res = $issue->save();
            if(!$res){
                throw new yii\base\Exception('审核记录保存失败');
            }
            $auditdata->audit_category = '复审';
            $auditdata->reject_reason_id = $reason_id;
            $auditdata->display_reason = $display_reason;
            $auditdata->audit_result = '审核拒绝';
            $auditdata->operator_id = $this->uid;
            $auditdata->audit_user_id = $this->uid;
            $auditdata->audit_when = new Expression('NOW()');
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
            //记录操作日志
            ServiceOperationLog::create_operation_log(json_encode($auditdata->attributes),
                $before,'复审拒绝','/review-audit/reject',$this->uid);

            $transaction->commit();
            $this->success('域名审核拒绝');

        } catch(Exception $e) {
            $transaction->rollBack();
            $this->error($e->getMessage());
        }
    }

    /**
     * 审核通过
     */
    public function actionPass(){

        //判断用户是否可以复审
        $this->check_user_rights();

        $domain_data_id = HtmlPurifier::process(trim(Yii::$app->request->post('audit_data_id','')));
        if(empty($domain_data_id)){
            $this->error('请选择要审核的域名');
        }
        $auditdata = AuditData::findOne(['guid'=>$domain_data_id]);
        if(null==$auditdata){
            $this->error('您要审核的域名不存在');
        }
        if(!('初审'==$auditdata['audit_category'] && '审核通过'==$auditdata['audit_result'])){

            $this->error('您要审核的记录已不能审核');
        }
        $before = json_encode($auditdata->attributes);
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
            $issue->audit_status = '复审审核通过';
            $issue->current_state = '已完成';
            $issue->memo = $memo;
            $issue->attachment = json_encode($attachment);
            $issue->domain = $auditdata->domain;
            $res = $issue->save();
            if(!$res){
                throw new yii\base\Exception('审核记录保存失败');
            }
            $auditdata->audit_category = '复审';
            $auditdata->audit_result = '审核通过';
            $auditdata->operator_id = $this->uid;
            $auditdata->audit_user_id = $this->uid;
            $auditdata->audit_when = new Expression('NOW()');
            $auditdata->modified = new Expression('NOW()');
            $flag = $auditdata->save();
            if(!$flag){
                throw new yii\base\Exception('审核记录保存失败');
            }
            //调用审核通过接口
            $username = Yii::$app->params['audit_admin']['username'];
            $pw = Yii::$app->params['audit_admin']['password'];

            $array = ['registrarId'=>$auditdata->registrar_epp_id,'contactId'=>$auditdata->contact_id,'domainName'=>$auditdata->domain];

            $regwebservice = new RegAuthGreenChannel($username,$pw);
            $returndata = $regwebservice->RegistrantChangeAuditPass($array);

            if(intval($returndata['code'])!==1){
                throw new yii\base\Exception($returndata['message']);
            }
            //发送邮件短信
            try{
                HelpAudit::send_message_email($auditdata,$this->uid);
            } catch(Exception $e) {
                Yii::error('发送邮件短信失败','audit');
            }
            //记录日志
            EppOperationLog::insert($this->uid, 'registrationAuditPass', '202.173.9.4', '9944', json_encode($array, JSON_UNESCAPED_UNICODE), 1, json_encode($returndata, JSON_UNESCAPED_UNICODE));

            //记录操作日志
            ServiceOperationLog::create_operation_log(json_encode($auditdata->attributes),
                $before,'复审通过','/review-audit/pass',$this->uid);

            $transaction->commit();
            $this->success('域名审核通过');
        } catch(Exception $e) {
            $transaction->rollBack();
            $this->error($e->getMessage());
        }
    }

    /**
     * 判断用户是否可以进行复审
     */
    private function check_user_rights(){
        //判断用户是否可以审核
        $userinfo = RegistryUser::findOne($this->uid);
        $audit_scope = empty($userinfo->audit_scope)?[]:json_decode($userinfo->audit_scope);
        if(empty($audit_scope) || !in_array('复审',$audit_scope)){
            $this->error('您没有复审权限');
        }
    }
} 