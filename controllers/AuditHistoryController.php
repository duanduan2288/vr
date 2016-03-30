<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015-9-14
 * Time: 11:37
 */

namespace app\controllers;


use app\models\AuditData;
use app\models\AuditReason;
use app\models\HelpAudit;
use app\models\Helper;
use Yii;
use yii\web\Controller;
use app\models\Issue;
use app\models\IssueOperation;
use app\models\UploadFile;
use app\models\AuditIssue;
use yii\db\Query;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;

class AuditHistoryController extends Controller{
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
     * 审核流水
     * @return string
     */
    public function actionIndex(){

        $domain = HtmlPurifier::process(trim(Yii::$app->request->get('domain','')));//域名
        $contact_id = HtmlPurifier::process(trim(Yii::$app->request->get('contact_id','')));//注册人id
        $start_date = HtmlPurifier::process(trim(Yii::$app->request->get('start_date',date('Y-m-01'))));
        $end_date = HtmlPurifier::process(trim(Yii::$app->request->get('end_date',date('Y-m-d'))));
        $registrar_epp_id = HtmlPurifier::process(trim(Yii::$app->request->get('registrar_epp_id','')));//注册商EPP_id
        $audit_status = HtmlPurifier::process(trim(Yii::$app->request->get('audit_status','')));//审核状态
        $audit_user_id = HtmlPurifier::process(trim(Yii::$app->request->get('audit_user_id','')));//审核人
        $feedback_status = HtmlPurifier::process(trim(Yii::$app->request->get('feedback_status','')));//回访状态

        $sort_audit_time = HtmlPurifier::process(trim(Yii::$app->request->get('sort_audit_time','desc')));
        //排序
        $sortarr = "audit_time {$sort_audit_time}";

        $querylist = new Query();
        $querylist->select('*')->from('audit_data')
            ->where('audit_result in ("审核退回","审核通过","审核拒绝")');

        if (!empty($domain))
        {
            $querylist->andWhere("domain LIKE '%{$domain}%'");
        }
        //注册人ID
        if (!empty($contact_id))
        {
            $querylist->andWhere("contact_id='{$contact_id}'");
        }

        if(!empty($start_date) && !empty($end_date))
        {
            $querylist->andWhere("created >='{$start_date}' and created <='{$end_date} 23:59:59'");
        }
        //注册商EPP_id
        if (!empty($registrar_epp_id))
        {
            $querylist->andWhere("registrar_epp_id='{$registrar_epp_id}'");
        }
        //审核人id
        if(!empty($audit_user_id)){
            $querylist->andWhere("audit_user_id='{$audit_user_id}'");
        }
        //回访状态
        if(!empty($feedback_status)){
            $querylist->andWhere("feedback_status='{$feedback_status}'");
        }
        //审核状态
        if(!empty($audit_status))
        {
            $audit_arr = explode('-',$audit_status);
            if(isset($audit_arr[0]) && !empty($audit_arr[0]))
            {
                $querylist->andWhere("audit_category='{$audit_arr[0]}'");
            }
            if(isset($audit_arr[1]) && !empty($audit_arr[1]))
            {
                $querylist->andWhere("audit_result='{$audit_arr[1]}'");
            }
        }

        $querylist->orderBy($sortarr);
        $count = $querylist->count();

        $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
        $querylist->offset($pages->offset)->limit($pages->limit);

        $data = $querylist->all();

        //经过处理的审核状态
        $audit_statuss = HelpAudit::get_audit_status();
        //所有注册商的epp_id
        $registrar_epp_ids = HelpAudit::get_registrar_epp_ids();
        //所有审核人员
        $audit_user_ids = HelpAudit::get_registrar_epp_ids();
        //所有回访状态
        $feedback_statuss = HelpAudit::get_feedback_status();
        //需要排序的th的url
        $url = Url::to(['audit-history/index',
            'start_date' => $start_date,
            'end_date' => $end_date,
            'domain' =>$domain,
            'contact_id'=>$contact_id,
            'registrar_epp_id'=>$registrar_epp_id,
            'audit_user_id'=>$audit_user_id,
            'feedback_status'=>$feedback_status,
            'audit_status'=>$audit_status,
            'sort_audit_time'=>$sort_audit_time=='desc'?'asc':'desc',
        ]);

        return $this->render('index',[
            'data' => $data,
            'pages'=>$pages,
            'domain' =>$domain,
            'contact_id'=>$contact_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'registrar_epp_id'=>$registrar_epp_id,
            'audit_user_id'=>$audit_user_id,
            'feedback_status'=>$feedback_status,
            'audit_status'=>$audit_status,
            'sort_audit_time'=>$sort_audit_time,
            'audit_statuss'=>$audit_statuss,
            'registrar_epp_ids'=>$registrar_epp_ids,
            'audit_user_ids'=>$audit_user_ids,
            'feedback_statuss'=>$feedback_statuss,
            'url'=>$url
        ]);
    }


    /**
     * 审核历史
     */
    public function actionHistory(){

        $id = HtmlPurifier::process(trim(Yii::$app->request->get('id','')));
        $guid = HtmlPurifier::process(trim(Yii::$app->request->get('guid','')));
        if(empty($id) || empty($guid))
        {
            $this->error('参数错误','','','layer.msg("参数错误");');
        }
        $auditdata = AuditData::findOne(['guid'=>$guid]);
        if(null===$auditdata){
            $this->error('记录不存在');
        }
        $issuemodel = new Issue();
        $issue = $issuemodel->getIssue($id);
        $issueoperation = IssueOperation::findBySql('SELECT * FROM issue_operation WHERE issue_id="'.$id.'" and operator_id>0 ORDER BY created ASC')->all();

        if($issue && !empty($issueoperation))
        {
            $list = [];
            $array = ['png','jpg','jpeg','gif'];
            foreach($issueoperation as $model)
            {
                if(empty($model->attached_data)) continue;

                $attached_data = json_decode($model->attached_data,true);
                $attachment = isset($attached_data['attachment'])?json_decode($attached_data['attachment']):array();
                $new = [];
                if(!empty($attachment))
                {
                    foreach($attachment as $guid)
                    {
                        $uploadfile = UploadFile::findOne(array('guid'=>$guid));
                        if($uploadfile)
                        {
                            if(in_array($uploadfile->filetype,$array)){
                                $new[] = '<a href="/upload/showuploadfile?id='.$guid.'" target="_blank" style="width:100px;height:100px;"><img src="/upload/showuploadfile?id='.$guid.'" style="width:100px;height:100px;" title="点击看大图" /></a>';
                            }else{
                                $new[] = '<a title="附件" href="/upload/showuploadfile?id='.$guid.'" target="_blank">'.$uploadfile->original_filename.'</a>';
                            }
                        }
                    }
                }
                //审核拒绝/退回理由
                if($attached_data['reject_reason_id']){
                    $reason = AuditReason::findOne($attached_data['reject_reason_id']);
                    $attached_data['reason'] = null!==$reason ? $reason['content'] : '';
                }
                $attached_data['attachment'] = $new;
                $attached_data['operator_id'] = $model->operator_id;
                $attached_data['created'] = $model->created;
                $list[] = $attached_data;
            }
            return $this->render('show',['issue'=>$issue,'issueoperation'=>$list,'auditdata'=>$auditdata]);
        }else{
            $this->error('暂无审核历史');
        }
    }

    /**
     * 查看域名详情
     * @return string
     */
    public function actionDetail(){
        $guid = HtmlPurifier::process(trim(Yii::$app->request->get('guid','')));
        if(empty($guid)){
            $this->error('请选择要查看的域名');
        }
        $auditdata = AuditData::findOne(['guid'=>$guid]);
        if(null===$auditdata){
            $this->error('您查看的域名不存在');
        }
        return $this->render('detail',['auditdata'=>$auditdata]);
    }
} 