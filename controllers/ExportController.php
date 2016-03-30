<?php
/**
 * Created by duan.
 * User: duan
 * Date: 2015-09-15
 * Time: 10:28
 */
namespace app\controllers;
use app\models\Helper;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use app\models\Service;
use app\extensions\ECSVExport;
use alexgx\phpexcel\PhpExcel;
use app\models\AuditData;
use app\models\RegistryUser;
use yii\helpers\HtmlPurifier;

class ExportController extends Controller{

    public $uid;

    public function init()
    {
        $user_id = Yii::$app->user->id;
        if(empty($user_id)){
            $this->error('请先登录');
        }
        $this->uid = $user_id;
    }

    /**
     * 审核流水
     */
    public function actionHistory(){
        $domain = HtmlPurifier::process(trim(Yii::$app->request->get('domain','')));//域名
        $contact_id = HtmlPurifier::process(trim(Yii::$app->request->get('contact_id','')));//注册人id
        $start_time = HtmlPurifier::process(trim(Yii::$app->request->get('start_date',date('Y-m-d',strtotime('-1 day')))));
        $end_time = HtmlPurifier::process(trim(Yii::$app->request->get('end_date',date('Y-m-d'))));
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

        if(!empty($start_time) && !empty($end_time))
        {
            $querylist->andWhere("created >='{$start_time}' and created <='{$end_time} 23:59:59'");
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
        $data = $querylist->all();
        $array = [];
        foreach($data as $list){
            $new = [];
            $new['domain'] = $list['domain'];
            $new['registrar_name'] = Service::get_company_name_by_id($list['registrar_id']);
            $new['agent_name'] = Service::get_agent_code_by_contact($list['contact_id']);
            $new['registrant'] = $list['registrant_organization'].'/'.$list['registrant_name'];
            $new['registrant_type'] = $list['registrant_type'];
            $new['period'] = $list['registered_years'];
            $new['organization'] = $list['organization_type'].'/'.($list['organization_type']=='营业执照'?$list['business_license']:$list['org_code']);
            $new['audit_status'] = $list['audit_category'].$list['audit_result'];
            $new['audit_time'] = $list['audit_time'];
            $new['audit_user_id'] = Service::get_user_name($list['audit_user_id']);
            $new['feedback_status'] = $list['feedback_status'];
            $array[] = $new;
        }
        array_unshift($array,
            ['域名','注册商','代理商','注册人组织/姓名','注册类型','注册年限','证件类型/证件编号','审核状态','审核时间','审核人','回访状态']);

        $filename = '审核流水：导出数据于'.date('Y-m-d'); //设置文件名
        $dtype = Yii::$app->request->get('dtype','csv');
        if($dtype=='csv'){
            $this->_out($array,$filename);
        }else{
            $this->wirte_excel($array,$filename);
        }
    }

    /**
     * 终端客户回访
     */
    public function actionFeedback(){
        $registrant_name = HtmlPurifier::process(trim(Yii::$app->request->get('registrant_name','')));
        $registrant_organization = HtmlPurifier::process(trim(Yii::$app->request->get('registrant_organization','')));
        $feedback_status = HtmlPurifier::process(trim(Yii::$app->request->get('feedback_status','')));
        $sort_status = HtmlPurifier::process(trim(Yii::$app->request->get('sort_status','desc')));
        //排序
        $sortarr = "feedback_status {$sort_status}";
        $data = new Query();
        $data->select('id,registrant_name,registrant_organization,telephone,mobile,email,domain_count,feedback_status');
        $data->from('customer_feedback');

        $userinfo = RegistryUser::findOne($this->uid);
        if(null==$userinfo){
            $this->error('用户不存在');
        }
        //用户可以回访的注册商
        if('注册商方式'==$userinfo->manage_type){
            $registrars = Service::get_registrar_id_by_user_id($this->uid);
            $data->andWhere("registrar_id in ( {$registrars['s']} )");
        }else{
            //用户可以回访的代理商
            $agent_codes = Service::get_agent_id_by_user_id($this->uid);
            $data->andWhere("substring(contact_id,6,3) in ( '{$agent_codes['s']}' )");
        }

        if (!empty($registrant_name))
        {
            $data->andWhere("registrant_name LIKE '%{$registrant_name}%'");
        }
        if (!empty($registrant_organization))
        {
            $data->andWhere("registrant_organization LIKE '%{$registrant_organization}%'");
        }
        if (!empty($feedback_status))
        {
            $data->andWhere("feedback_status='{$feedback_status}'");
        }

        $data->orderBy($sortarr);
        $list = $data->all();
        array_unshift($list,
            ['ID','终端客户','客户公司','联系电话','联系手机','联系邮箱','注册域名数量','回访状态']);

        $filename = '终端客户回访：导出数据于'.date('Y-m-d'); //设置文件名
        $dtype = Yii::$app->request->get('dtype','csv');
        if($dtype=='csv'){
            $this->_out($list,$filename);
        }else{
            $this->wirte_excel($list,$filename);
        }
    }

    /**
     * 域名列表
     */
    public function actionDomainList(){

        $registrant_name =HtmlPurifier::process(trim(Yii::$app->request->get('registrar_name','')));
        $registrant_organization = HtmlPurifier::process(trim(Yii::$app->request->get('registrar_organization','')));
        $start_date = HtmlPurifier::process(trim(Yii::$app->request->get('start_date','')));
        $end_date = HtmlPurifier::process(trim(Yii::$app->request->get('end_date','')));
        $time_type = HtmlPurifier::process(trim(Yii::$app->request->get('time_type','contact_update')));
        $audit_status = HtmlPurifier::process(trim(Yii::$app->request->get('audit_status','')));

        $sort_update_date = HtmlPurifier::process(trim(Yii::$app->request->get('sort_update_date','desc')));
        $sort_created = HtmlPurifier::process(trim(Yii::$app->request->get('sort_created','desc')));
        $sort_status = HtmlPurifier::process(trim(Yii::$app->request->get('sort_status','desc')));
        //排序
        $sortarr = "created {$sort_created}, service_start_time {$sort_update_date} ,audit_result {$sort_status}";

        $auditdata = AuditData::find()
            ->where("(audit_category='初审' and audit_result='审核通过') or audit_category='复审'");

        $userinfo = RegistryUser::findOne($this->uid);
        if(null==$userinfo){
            $this->error('用户不存在');
        }
        //用户可以回访的注册商
        if('注册商方式'==$userinfo->manage_type){
            $registrars = Service::get_registrar_id_by_user_id($this->uid);
            $auditdata->andWhere("registrar_id in ( {$registrars['s']} )");
        }else{
            //用户可以回访的代理商
            $agent_codes = Service::get_agent_id_by_user_id($this->uid);
            $auditdata->andWhere("substring(contact_id,6,3) in ( '{$agent_codes['s']}' )");
        }

        if(!empty($registrant_name)){
            $auditdata->andWhere("registrant_name='{$registrant_name}'");
        }
        if(!empty($registrant_organization)){
            $auditdata->andWhere("registrant_organization='{$registrant_organization}'");
        }

        if(!empty($start_date) && !empty($end_date))
        {
            $auditdata->andWhere("{$time_type} >='{$start_date}' and {$time_type} <='{$end_date} 23:59:59'");
        }
        //审核状态
        if(!empty($audit_status))
        {
            $audit_arr = explode('-',$audit_status);
            if(isset($audit_arr[0]) && !empty($audit_arr[0]))
            {
                $auditdata->andWhere("audit_category='{$audit_arr[0]}'");
            }
            if(isset($audit_arr[1]) && !empty($audit_arr[1]))
            {
                $auditdata->andWhere("audit_result='{$audit_arr[1]}'");
            }
        }
        $auditdata->orderBy($sortarr);
        $data = $auditdata->all();
        $array = [];
        foreach($data as $list){
            $new = [];
            $new['domain'] = $list['domain'];
            $new['domain_agent_tel'] = $list['domain_agent_tel'];
            $new['domain_agent_mobile'] = $list['domain_agent_mobile'];
            $new['domain_agent_email'] = $list['domain_agent_email'];
            $new['audit_status'] = $list['audit_category'].$list['audit_result'];
            $new['need_feedback'] = $list['need_feedback'];
            $new['service_start_time'] = $list['service_start_time'];
            $new['contact_update'] = $list['contact_update'];
            $new['feedback_status'] = $list['feedback_status'];
            $array[] = $new;
        }
        array_unshift($array,
            ['域名','联系电话','联系手机','联系邮箱','审核状态','是否需要回访','注册时间','联系人更新时间','回访状态']);

        $filename = '注册域名列表：导出数据于'.date('Y-m-d'); //设置文件名
        $dtype = Yii::$app->request->get('dtype','csv');
        if($dtype=='csv'){
            $this->_out($array,$filename);
        }else{
            $this->wirte_excel($array,$filename);
        }
    }

    /**
     * 客户回访统计
     */
    public function actionCensus()
    {
        $registrant_id = HtmlPurifier::process(trim(Yii::$app->request->get('registrant_id', '')));
        $agent_code = HtmlPurifier::process(trim(Yii::$app->request->get('agent_code', '')));

        $auditdata = new Query();
        $auditdata->select('`registrar_id`,`contact_id`,`update_count`,`domain`')
            ->from('audit_data')
            ->where("(audit_category='初审' and audit_result='审核通过') or audit_category='复审'");

        //用户可以回访的注册商
        $registrars = Service::get_registrar_id_by_user_id($this->uid);
        $auditdata->andWhere("registrar_id in ( {$registrars['s']} )");
        //用户可以回访的代理商
        $agent_codes = Service::get_agent_id_by_user_id($this->uid);
        $auditdata->andWhere("substring(contact_id,6,3) in ( '{$agent_codes['s']}' )");

        if (!empty($registrant_id)) {
            $auditdata->andWhere("registrar_id={$registrant_id}");
        }
        if (!empty($agent_code)) {
            $auditdata->andWhere("substring(contact_id,6,3)='{$agent_code}'");
        }
        $auditdata->orderBy('registrar_epp_id asc');
        $data = $auditdata->all();
        $array = [];
        foreach($data as $list){
            $new = [];
            $new['registrar_name'] = Service::get_company_name_by_id($list['registrar_id']);
            $code = Service::get_agent_code_by_contact($list['contact_id']);
            $new['agent_name'] = Service::get_agent_name_by_code($code);
            $new['domain'] = $list['domain'];
            $new['is_once'] = $list['update_count']>0?'否':'是';
            $new['update_count'] = $list['update_count'];
            $array[] = $new;
        }
        array_unshift($array,
            ['注册商','代理商','域名','是否一次通过','修改次数']);

        $filename = '客户回访统计：导出数据于'.date('Y-m-d'); //设置文件名
        $dtype = Yii::$app->request->get('dtype','csv');
        if($dtype=='csv'){
            $this->_out($array,$filename);
        }else{
            $this->wirte_excel($array,$filename);
        }
    }

    /**
     * 财务明细
     */
    public function actionFinance()
    {
        $search_domain = trim(Yii::$app->request->get('search_domain', ''));
        $search_name = trim(Yii::$app->request->get('search_name', ''));
        $start_date = trim(Yii::$app->request->get('start_date', ''));
        $end_date = trim(Yii::$app->request->get('end_date', ''));
        $w = trim(Yii::$app->request->get('w', ''));
        
        $auditdata = new Query();
        $auditdata->select('`registrar_name`,`operation_type`,`sequence_number`,`domain_name`,`operation_deadline`,`cost`,
            `cost_type`,`operator`,`operation_date`,`remarks`')->from('data_finance')
            ->where("1=1");

        if (!empty($w)) {
            $auditdata->andWhere(" operation_type = '域名创建费用' AND cost_type = '扣款' ");
            $auditdata->andWhere("domain_name not in(select domain from audit_data)");
        } 
        if (!empty($search_domain)) {
            $auditdata->andWhere("domain_name LIKE '%{$search_domain}%'");
        }
        if (!empty($search_name)) {
            $auditdata->andWhere("registrar_name LIKE '%{$search_name}%'");
        }
        if (!empty($start_date)) {
            $auditdata->andWhere("operation_date >='{$start_date}'");
        }
        if (!empty($end_date)) {
            $auditdata->andWhere("operation_date <='{$end_date} 23:59:59'");
        }
        $auditdata->orderBy('id asc');
        $data = $auditdata->all();
        $array = [];
        foreach($data as $list){
            $new = [];
            $new['registrar_name'] = $list['registrar_name'];
            $new['operation_type'] = $list['operation_type'];
            $new['sequence_number'] = $list['sequence_number'];
            $new['domain_name'] = $list['domain_name'];
            $new['operation_deadline'] = $list['operation_deadline'];
            $new['cost'] = $list['cost'];
            $new['cost_type'] = $list['cost_type'];
            $new['operator'] = $list['operator'];
            $new['operation_date'] = $list['operation_date'];
            $new['remarks'] = $list['remarks'];
            $array[] = $new;
        }
        array_unshift($array,
            ['注册商名称','操作类型','单据号码（序列号）','商标域名','操作期限','费用','费用类型','操作员','操作日期','备注']);

        $filename = '财务明细：导出数据于'.date('Y-m-d'); //设置文件名
        if (!empty($w)) {
            $filename = '未上传审核资料的域名'.$filename;
        } 
        $dtype = Yii::$app->request->get('dtype','csv');
        if($dtype=='csv'){
            $this->_out($array,$filename);
        }else{
            $this->wirte_excel($array,$filename);
        }
    }

    /**
     * 域名操作明细
     */
    public function actionDomain()
    {
        $search_domain = trim(Yii::$app->request->get('search_domain', ''));
        $start_date = trim(Yii::$app->request->get('start_date', ''));
        $end_date = trim(Yii::$app->request->get('end_date', ''));
        
        $auditdata = new Query();
        $auditdata->select('`domain_name`,`registrar_id`,`command`,`operation_date`')
            ->from('data_domain')
            ->where("1=1");

        if (!empty($search_domain)) {
            $auditdata->andWhere("domain_name LIKE '%{$search_domain}%'");
        }
        if (!empty($start_date)) {
            $auditdata->andWhere("operation_date >='{$start_date}'");
        }
        if (!empty($end_date)) {
            $auditdata->andWhere("operation_date <='{$end_date} 23:59:59'");
        }
        $auditdata->orderBy('id asc');
        $data = $auditdata->all();
        $array = [];
        foreach($data as $list){
            $new = [];
            $new['domain_name'] = $list['domain_name'];
            $new['registrar_id'] = $list['registrar_id'];
            $new['command'] = $list['command'];
            $new['operation_date'] = $list['operation_date'];
            $array[] = $new;
        }
        array_unshift($array,
            ['商标域名','注册商ID','命令','操作日期']);

        $filename = '域名操作明细：导出数据于'.date('Y-m-d'); //设置文件名
        $dtype = Yii::$app->request->get('dtype','csv');
        if($dtype=='csv'){
            $this->_out($array,$filename);
        }else{
            $this->wirte_excel($array,$filename);
        }
    }

    /**
     * 公用输出方法
     * @param $array
     * @param $filename
     */
    public function _out($array,$filename)
    {
        $filename = $filename.'.csv';
        $csv = new ECSVExport($array);
        $csv->includeColumnHeaders=false;
        $output = $csv->toCSV(); // returns string by default
        $btype = Helper::my_get_browser();
        if($btype=='IE'){
            $filename = urlencode($filename);
        }
        Yii::$app->response->sendContentAsFile(iconv('UTF-8','GBK//IGNORE',$output),$filename,['mimeType'=>"text/csv",'inline'=>false]);
    }

    /**
     * 把数据保存到EXCEL
     */
    public  function wirte_excel($arrDataList,$title){
        $xlsfilename = $title.'.xls';
        $btype = Helper::my_get_browser();
        if($btype=='IE'){
            $xlsfilename = urlencode($xlsfilename);
        }
        if(!empty($arrDataList)){
            $objPHPExcel = new PhpExcel();
            $phpexcel = $objPHPExcel->create();
            $phpexcel->getProperties()->setCreator("注册局")
                ->setLastModifiedBy("注册局")
                ->setTitle($title);

            $phpexcel->getActiveSheet()->fromArray(
                $arrDataList, // 赋值的数组
                NULL, // 忽略的值,不会在excel中显示
                'A1' // 赋值的起始位置
            );
            $objPHPExcel->responseFile($phpexcel,$xlsfilename);
        }
    }
}