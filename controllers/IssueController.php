<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-11-25
 * Time: 上午10:52
 */
namespace app\controllers;
use yii\web\Controller;
use Yii;
use app\models\RegistryUser;
use yii\db\Query;
use app\models\Dictionary;
use app\models\Service;
use yii\data\Pagination;
use app\models\UploadFile;
use app\models\Issue;
use app\models\IssueOperation;
use app\models\AuditIssue;

class IssueController extends Controller{

    const  ISSUE_PAGE_SIZE = 15;
    private $issueTypeMaping = array(
        'ComplianceIssue' => '/issue/complianceView',
        'ServiceIssue' => '/issue/serviceView',
        'ReviewIssue'=>'/issue/view',
        'TechnologyIssue'=>'/issue/view',
        'FinanceIssue'=>'/issue/view',
        'DomainPolicyIssue'=>'/issue/view',
        'ChannelPolicyIssue'=>'/issue/view',
        'MarketSupportIssue'=>'/issue/view',
        'OthersIssue'=>'/issue/view',
    );

    /**
     * 通用工单列表
     * @return [type] [description]
     */
    public function actionIndex()
    {
        $uid = Yii::$app->user->id;
        $user = RegistryUser::findOne($uid);
        if (empty($user)) {
            $this->redirect('/site/login');
        }
        $s = Yii::$app->request->get('s', '');
        $name = Yii::$app->request->get('name', '');
        $issue_id = Yii::$app->request->get('issue_id', '');
        $agent_name = Yii::$app->request->get('agent_name','');

        //操作员只能操作自己可管理的代理商 start
        $operator_id = Dictionary::$employee_role_code['operator'];
        $flag = $operator_id == $user->user_role?true:false;
        $agent_ids = Service::get_registrar_id_by_user_id($uid);
        $query = new Query();
        $query->select('*')->from('issue')->where('type="AuditIssue"');
        if ($flag) {
            $query->andWhere("creator_company_id in ( {$agent_ids['s']} )");
        }
        //操作员只能操作自己可管理的代理商 end

        if(!empty($agent_name)){
            $string = Service::get_company_id_by_name($agent_name);
            $query->andWhere("creator_company_id in ( {$string} )");
        }
        $start_date = Yii::$app->request->get('start_date', date('Y-m-01'));
        $end_date = Yii::$app->request->get('end_date', date('Y-m-d'));
        $res = $this->get_condition($query,$s,$start_date,$end_date,$name,$issue_id,0);

        return $this->render('index', array('model' => $res['model'],
            'pages' => $res['pages'],
            's'=>$s,
            'start_date'=>$start_date,
            'end_date'=>$end_date,
            'name'=>$name,
            'issue_id'=>$issue_id,
            'agent_name'=>$agent_name,
            'title'=>'所有'
        ));
    }

    /**
     * 通用工单详情
     * @return [type] [description]
     */
    public function actionView()
    {

        $uid = Yii::$app->user->id;
        if(empty($uid))
        {
            $this->redirect('/site/login');
        }
        $id = Yii::$app->request->get('id','');

        if(empty($id))
        {
            $this->error('参数错误','','','layer.msg("参数错误");');
        }
        $issuemodel = new Issue();
        $issue = $issuemodel->getIssue($id);
        $issueoperation = IssueOperation::findBySql('SELECT * FROM issue_operation WHERE issue_id="'.$id.'" ORDER BY created ASC');

        if($issue && !empty($issueoperation))
        {
            $list = [];
            $array = ['png','jpg','jpeg','gif'];
            foreach($issueoperation as $model)
            {
                if(!empty($model->attached_data))
                {
                    $attached_data = json_decode($model->attached_data,true);
                    $content = isset($attached_data['content'])? nl2br($attached_data['content']):'';
                    $attachment = isset($attached_data['attachment'])?json_decode($attached_data['attachment']):array();
                    $new = [];
                    if(!empty($attachment))
                    {
                        foreach($attachment as $guid)
                        {
                            $uploadfile = UploadFile::model()->findByAttributes(array('guid'=>$guid));
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
                    $list[] = array('created'=>$model->created,'creator'=>$model->operator_id,
                        'operation_date'=>$model->operation_date,'content'=>$content,'attachment'=>$new);
                }else{
                    continue;
                }

            }
            $statuslist = $this->get_issue_type($issue->current_state);
            if($issue->type=='ComplianceIssue'){
                return $this->render('complianceshow',['issue'=>$issue,'issueoperation'=>$list,'statuslist'=>$statuslist]);
            }else{
                return $this->render('show',['issue'=>$issue,'issueoperation'=>$list,'statuslist'=>$statuslist]);
            }
        }
    }

    /**
     * 处理通用工单
     * @return [type] [description]
     */
    public function actionProcess()
    {
        $user_id = Yii::$app->user->id;
        if(!$user_id){
            $this->redirect('/site/login');
        }
       $content = Yii::$app->request->getPost('content','');
       $issue_id = Yii::$app->request->getPost('issue_id','');
       $current_state = Yii::$app->request->getPost('current_state','');
       $attachment = Yii::$app->request->getPost('attachment','');
       if(!empty($issue_id) && !empty($content) && !empty($current_state)){
           $issue = Issue::model()->getIssue($issue_id);
           if(!$issue){
               $this->error('工单不存在');
           }
           if($issue->assignee_id!=0 && $issue->assignee_id!=$user_id){
               $this->error('您不能处理此工单');
           }
           if ($issue->current_state=='已完成') {
               $this->error('此工单已完成不能再操作');
           }
           if ($issue->current_state=='已关闭') {
               $this->error('此工单已取消不能再操作');
           }
           //$issue->current_state = $current_state;
           $issue->modified = new CDbExpression ( 'NOW()' );
           $issue->assignee_id = $user_id;
           $issue->content = htmlspecialchars($content);
           $issue->attachment = json_encode($attachment);
           $res = $issue->customIssueState(Yii::$app->user->id,$current_state);
           if($res){
               $msg = '您'.$issue->name.'的工单已经处理，请查看';
               $sss = Service::send_notification(1,'代理商',$msg,$user_id,$issue->creator,'普通',$issue->id);
               $pages = $this->actionGetUrl($issue->type);
               $this->redirect(array($pages,'id'=>$issue_id));
           }else{
               $this->error('保存失败');
           }
       }else{
           $this->error('参数错误');
       }
    }

    /**
     * 取消工单
     * @return [type] [description]
     */
    public function actionClosed()
    {
        $user_id = Yii::$app->user->id;
        if (!$user_id) {
            $this->redirect('/site/login');
        }
        $id = Yii::$app->request->get('id', '');
        if(empty($id)){
            $this->error('参数错误');
        }
        $issue = Issue::model()->getIssue($id);
        if(!$issue){
            $this->error('工单不存在');
        }
        if($issue->assignee_id!=0 && $issue->assignee_id!=$user_id){
            $this->error('您不能取消此工单');
        }
        if($issue->current_state!==Issue::ISSUE_STATE_COMMITTED){
            $this->error('工单正在处理中，已不能取消');
        }
        $issue->content = '';
        $issue->attachment = json_encode(array());
        $issue->assignee_id = $user_id;
        $issue->modified = new CDbExpression ('NOW()');
        $issue->current_state = Issue::ISSUE_STATE_CANCEL;
        $res = $issue->save();
        if ($res) {
            $msg = $issue->name.'的工单已取消，请查看';
            Service::send_notification(1,'代理商',$msg,$user_id,$issue->creator,$issue->priority,$issue->id);
            $this->redirect('/issue/index');
        }else{
            $this->error('工单取消失败');
        }
    }

///////////////////////合规通知//////////////////////////////////////////////

    /**
     * 合规通知列表
     * @return [type] [description]
     */
    public function actionComplianceIssue()
    {
        $uid = Yii::$app->user->id;
        $user = RegistrarUser::model()->findByPk($uid);
        if (empty($user)) {
            $this->redirect('/site/login');
        }
        $criteria = new CDbCriteria;
        $criteria->addCondition("type='ComplianceIssue'") ;
        $s = Yii::$app->request->get('s', '');
        $name = Yii::$app->request->get('name', '');
        $issue_id = Yii::$app->request->get('issue_id', '');
        $agent_name = Yii::$app->request->get('agent_name','');

        //操作员只能操作自己可管理的代理商 start
        $operator_id = Dictionary::$employee_role_code['operator'];
        $flag = $operator_id == $user->user_role?true:false;
        $agent_ids = Service::get_agent_id_by_user_id($uid);
        if ($flag) {
            $criteria->addCondition("assignee_company_id in ( {$agent_ids['s']} )");
        }
        //操作员只能操作自己可管理的代理商 end

        if(!empty($agent_name)){
            $string = Service::get_company_id_by_name($agent_name);
            $criteria->addCondition("assignee_company_id in ( {$string} )");
        }
        $start_date = Yii::$app->request->get('start_date', date('Y-m-01'));
        $end_date = Yii::$app->request->get('end_date', date('Y-m-d'));
        $res = $this->get_condition($criteria,$s,$start_date,$end_date,$name,$issue_id,0);
        $this->render('compliance', array('model' => $res['model'],
            'pages' => $res['pages'],
            's'=>$s,
            'start_date'=>$start_date,
            'end_date'=>$end_date,
            'name'=>$name,
            'issue_id'=>$issue_id,
            'agent_name'=>$agent_name,
            'title'=>'合规通知',
        ));
    }

    /**
     * 创建合规通知
     * @return [type] [description]
     */
    public function actionCreateComplianceIssue()
    {
        $uid = Yii::$app->user->id;
        $user = RegistrarUser::model()->findByPk($uid);
        if (empty($user)) {
            $this->redirect('/site/login');
        }
        $agents = $this->get_all_agents();
        if(isset($_POST) && !empty($_POST)){
            $assignee_company_id = Yii::$app->request->post('assignee_company_id', '');
            $name = Yii::$app->request->post('name', '');
            $content = Yii::$app->request->post('content', '');
            $attachment = Yii::$app->request->post('attachment', array());
            $priority = Yii::$app->request->post('priority', '普通');

            if (empty($name)) {
                $this->error('请填写标题');
            }
            if (empty($content)) {
                $this->error('请填写内容');
            }
            $issue = new AuditIssue();
            $issue->guid = Service::create_guid();
            $issue->attachment = json_encode($attachment);
            $issue->content = htmlspecialchars($content);
            $issue->name = htmlspecialchars($name);
            $issue->creator_company_id = 0;
            $issue->issue_from = '注册商';
            $issue->attached_id = 0;
            $issue->attached_table = '';
            $issue->assignee_company_table = 'agent';
            $issue->assignee_company_id = $assignee_company_id;
            $issue->assignee_id = 0;
            $issue->priority = $priority;
            $issue->created = new CDbExpression ( 'NOW()' );
            $issue->modified = new CDbExpression ( 'NOW()' );
            $res = $issue->commitIssue(Yii::$app->user->id);
            if ($res) {
                //发送消息
                $msg = '您有新的合规通知需要处理，请查看';
                Service::send_notification(2,'代理商',$msg,Yii::$app->user->id,0,$priority,$issue->id,'','合规通知',$assignee_company_id);
                $this->success('合规创建成功');
            } else {
                $this->error('合规创建失败');
            }
        }
        //操作员只能操作自己可管理的代理商 start
        $operator_id = Dictionary::$employee_role_code['operator'];
        $flag = $operator_id == $user->user_role?true:false;
        $agent_ids = Service::get_agent_id_by_user_id($uid);
        $this->render('createcompliance',[
            'agents'=>$agents,
            'flag'=>$flag,
            'agent_ids'=>$agent_ids['a']
        ]);
    }

    /**
     * 合规通知详情
     * @return [type] [description]
     */
    public function actionComplianceView()
    {

        $uid = Yii::$app->user->id;
        if(empty($uid)){
            $this->redirect('/site/login');
        }
        $id = Yii::$app->request->get('id','');

        if(empty($id)){
            $this->error('参数错误','','','layer.msg("参数错误");');
        }
        $issue = Issue::model()->getIssue($id);
        $issueoperation = IssueOperation::model()->findAllBySql('SELECT * FROM issue_operation WHERE issue_id="'.$id.'" ORDER BY created ASC');

        if($issue && !empty($issueoperation)){
            $list = [];
            $array = ['png','jpg','jpeg','gif'];
            foreach($issueoperation as $model)
            {
                if(!empty($model->attached_data))
                {
                    $attached_data = json_decode($model->attached_data,true);
                    $content = isset($attached_data['content'])? nl2br($attached_data['content']):'';
                    $attachment = isset($attached_data['attachment'])?json_decode($attached_data['attachment']):array();
                    $new = [];
                    if(!empty($attachment))
                    {
                        foreach($attachment as $guid)
                        {
                            $uploadfile = UploadFile::model()->findByAttributes(array('guid'=>$guid));
                            if($uploadfile)
                            {
                                if(in_array($uploadfile->filetype,$array))
                                {
                                    $new[] = '<a href="/upload/showuploadfile?id='.$guid.'" target="_blank" style="width:100px;height:100px;"><img src="/upload/showuploadfile?id='.$guid.'" style="width:100px;height:100px;" title="点击看大图" /></a>';
                                }else{
                                    $new[] = '<a title="附件" href="/upload/showuploadfile?id='.$guid.'" target="_blank">'.$uploadfile->original_filename.'</a>';
                                }
                            }
                        }
                    }
                    $list[] = array('created'=>$model->created,'creator'=>$model->operator_id,
                        'operation_date'=>$model->operation_date,'content'=>$content,'attachment'=>$new);
                }else{
                    continue;
                }

            }
            $statuslist = $this->get_issue_type($issue->current_state);
            if($issue->type=='ComplianceIssue'){
                $this->render('complianceshow',['issue'=>$issue,'issueoperation'=>$list,'statuslist'=>$statuslist]);
            }else{
                $this->render('show',['issue'=>$issue,'issueoperation'=>$list,'statuslist'=>$statuslist]);
            }
        }
    }

    /****
     * 处理合规通知
     */
    public function actionProcessCompliance()
    {
        $user_id = Yii::$app->user->id;
        if(!$user_id){
            $this->redirect('/site/login');
        }
        $content = Yii::$app->request->getPost('content','');
        $issue_id = Yii::$app->request->getPost('issue_id','');
        $attachment = Yii::$app->request->getPost('attachment','');
        $current_state = Yii::$app->request->getPost('current_state','');
        if(!empty($issue_id) && !empty($content)){
            $issue = Issue::model()->getIssue($issue_id);
            if(!$issue){
                $this->error('通知不存在');
            }
            if($issue->creator!=$user_id){
                $this->error('您不能处理此通知');
            }
            if ($issue->current_state=='已完成') {
               $this->error('此通知已完成不能再操作');
            }
            if ($issue->current_state=='已关闭') {
               $this->error('此通知已取消不能再操作');
            }
            //$issue->current_state = $current_state;
            $issue->modified = new CDbExpression ( 'NOW()' );
            $issue->content = htmlspecialchars($content);
            if(!empty($current_state)){
                $issue->current_state = $current_state;
            }
            $issue->attachment = json_encode($attachment);
            $res = $issue->save();
            if($res){

                $msg = '您'.$issue->name.'的合规通知有新的操作，请查看';
                if($issue->assignee_id==0){
                    Service::send_notification(2,'代理商',$msg,Yii::$app->user->id, 0,$issue->priority,$issue->id,'','合规通知',$issue->assignee_company_id);
                }else{
                    Service::send_notification(1,'代理商',$msg,Yii::$app->user->id, $issue->assignee_id,$issue->priority,$issue->id);
                }
                $pages = '/issue/complianceView';
                $this->redirect(array($pages,'id'=>$issue_id));
            }else{
                $this->error('保存失败');
            }
        }else{
            $this->error('参数错误');
        }
    }

///////////////////////客户服务///////////////////////////////////////////////

    /**
     * 客户服务列表
     * @return [type] [description]
     */
    public function actionServiceIssue()
    {
        $uid = Yii::$app->user->id;
        if (empty($uid)) {
            $this->redirect('/site/login');
        }
        $criteria = new CDbCriteria;
        $criteria->addCondition("type='ServiceIssue'") ;
        $s = Yii::$app->request->get('s', '');
        $name = Yii::$app->request->get('name', '');
        $issue_id = Yii::$app->request->get('issue_id', '');
        $start_date = Yii::$app->request->get('start_date', date('Y-m-01'));
        $end_date = Yii::$app->request->get('end_date', date('Y-m-d'));
        $res = $this->get_condition($criteria,$s,$start_date,$end_date,$name,$issue_id,0);
        $this->render('service', array('model' => $res['model'],
            'pages' => $res['pages'],
            's'=>$s,
            'name'=>$name,
            'issue_id'=>$issue_id,
            'start_date'=>$start_date,
            'end_date'=>$end_date,
            'title'=>'合规通知'
        ));
    }

    /**
     * 创建客户服务
     * @return [type] [description]
     */
    public function actionCreateServiceIssue()
    {
        $uid = Yii::$app->user->id;
        $user = RegistrarUser::model()->findByPk($uid);
        if (empty($user)) {
            $this->redirect('/site/login');
        }
        $agents = $this->get_all_agents();
        if(isset($_POST) && !empty($_POST)){
            // print_r($_POST);die;
            $name = Yii::$app->request->getPost('name', '');
            $content = Yii::$app->request->getPost('content', '');
            $attachment = Yii::$app->request->getPost('attachment', array());
            $priority = Yii::$app->request->getPost('priority', '普通');

            $big_type = Yii::$app->request->getPost('big_type', '');
            $small_type = Yii::$app->request->getPost('small_type', '');
            $agent_name = Yii::$app->request->getPost('agent_name', '');
            $diqu = Yii::$app->request->getPost('diqu', '');
            $contact = Yii::$app->request->getPost('contact', '');
            $cellphone = Yii::$app->request->getPost('cellphone', '');
            $customer_type = Yii::$app->request->getPost('customer_type', '');

            if (empty($name)) {
                $this->error('请填写标题');
            }
            if (empty($big_type)) {
                $this->error('请选择服务类型');
            }
            if (empty($small_type)) {
                $this->error('请选择细分类型');
            }
            if (empty($customer_type)) {
                $this->error('请选择客户类型');
            }
            if (empty($agent_name)) {
                $this->error('请填写代理商');
            }
            if (empty($diqu)) {
                $this->error('请选择地区');
            }
            if (empty($contact)) {
                $this->error('请填写联系人');
            }
            if (empty($cellphone)) {
                $this->error('请填写联系电话');
            }
            if (empty($content)) {
                $this->error('请填写内容');
            }
            $issue = new ServiceIssue();
            $issue->guid = Service::create_guid();
            $issue->attachment = json_encode($attachment);
            $issue->content = htmlspecialchars($content);
            $issue->name = htmlspecialchars($name);
            $issue->creator_company_id = 0;
            $issue->issue_from = '注册商';
            $issue->attached_id = 0;
            $issue->attached_table = '';
            $issue->assignee_company_table = 'registrar_user';
            $issue->assignee_company_id = 9999;
            $issue->assignee_id = 0;
            $issue->priority = $priority;

            $issue->big_type = $big_type;
            $issue->small_type = $small_type;
            $issue->agent_name = htmlspecialchars($agent_name);
            $issue->diqu = $diqu;
            $issue->contact = $contact;
            $issue->cellphone = $cellphone;
            $issue->customer_type = $customer_type;
            $issue->created = new CDbExpression ( 'NOW()' );
            $issue->modified = new CDbExpression ( 'NOW()' );
            $res = $issue->commitIssue(Yii::$app->user->id);
            if ($res) {
                $this->success('客户服务添加成功');
            } else {
                $this->error('客户服务添加失败');
            }
        }
        //操作员只能操作自己可管理的代理商 start
        $operator_id = Dictionary::$employee_role_code['operator'];
        $flag = $operator_id == $user->user_role?true:false;
        $agent_ids = Service::get_agent_id_by_user_id($uid);
        //操作员只能操作自己可管理的代理商 end
        $this->render('createservice',[
            'agents'=>$agents,
            'flag'=>$flag,
            'agent_ids'=>$agent_ids['a']
        ]);
    }

    /**
     * 客户服务详情
     * @return [type] [description]
     */
    public function actionServiceView()
    {

        $uid = Yii::$app->user->id;
        if(empty($uid)){
            $this->redirect('/site/login');
        }
        $id = Yii::$app->request->get('id','');

        if(empty($id)){
            $this->error('参数错误','','','layer.msg("参数错误");');
        }
        $issue = Issue::model()->getIssue($id);
        $issueoperation = IssueOperation::model()->findAllBySql('SELECT * FROM issue_operation WHERE issue_id="'.$id.'" ORDER BY created ASC');

        if($issue && !empty($issueoperation)){
            $list = [];
            $array = ['png','jpg','jpeg','gif'];
            foreach($issueoperation as $model)
            {
                if(!empty($model->attached_data))
                {
                    $attached_data = json_decode($model->attached_data,true);
                    $content = isset($attached_data['content'])? nl2br($attached_data['content']):'';
                    $attachment = isset($attached_data['attachment'])?json_decode($attached_data['attachment']):array();
                    $new = [];
                    if(!empty($attachment))
                    {
                        foreach($attachment as $guid)
                        {
                            $uploadfile = UploadFile::model()->findByAttributes(array('guid'=>$guid));
                            if($uploadfile)
                            {
                                if(in_array($uploadfile->filetype,$array))
                                {
                                    $new[] = '<a href="/upload/showuploadfile?id='.$guid.'" target="_blank" style="width:100px;height:100px;"><img src="/upload/showuploadfile?id='.$guid.'" style="width:100px;height:100px;" title="点击看大图" /></a>';
                                }else{
                                    $new[] = '<a title="附件" href="/upload/showuploadfile?id='.$guid.'" target="_blank">'.$uploadfile->original_filename.'</a>';
                                }
                            }
                        }
                    }
                    $list[] = array('created'=>$model->created,'creator'=>$model->operator_id,
                        'operation_date'=>$model->operation_date,'content'=>$content,'attachment'=>$new);
                }else{
                    continue;
                }

            }
            $statuslist = $this->get_issue_type($issue->current_state);
            if($issue->type=='ServiceIssue'){
                $this->render('serviceshow',['issue'=>$issue,'issueoperation'=>$list,'statuslist'=>$statuslist]);
            }else{
                $this->render('show',['issue'=>$issue,'issueoperation'=>$list,'statuslist'=>$statuslist]);
            }
        }
    }

    /****
     * 处理客户服务
     */
    public function actionProcessService()
    {
        $user_id = Yii::$app->user->id;
        if(!$user_id){
            $this->redirect('/site/login');
        }
        $content = Yii::$app->request->getPost('content','');
        $issue_id = Yii::$app->request->getPost('issue_id','');
        $attachment = Yii::$app->request->getPost('attachment','');
        $current_state = Yii::$app->request->getPost('current_state','');
        if(!empty($issue_id) && !empty($content)){
            $issue = Issue::model()->getIssue($issue_id);
            if(!$issue){
                $this->error('客户服务记录不存在');
            }
            if ($issue->current_state=='已完成') {
               $this->error('此客户服务记录已完成不能再操作');
            }
            if ($issue->current_state=='已关闭') {
               $this->error('此客户服务记录已取消不能再操作');
            }
            if($issue->creator!=$user_id&&$issue->assignee_id!=0&&$issue->assignee_id!=$user_id){
                $this->error('您不能处理此客户服务记录');
            }
            $issue->modified = new CDbExpression ( 'NOW()' );
            $issue->content = htmlspecialchars($content);
            if(!empty($current_state)){
                $issue->current_state = $current_state;
            }
            $issue->attachment = json_encode($attachment);
            $res = $issue->save();
            if($res){
                $pages = '/issue/serviceView';
                $this->redirect(array($pages,'id'=>$issue_id));
            }else{
                $this->error('保存失败');
            }
        }else{
            $this->error('参数错误');
        }
    }

    /**
     * 取消客户服务
     * @return [type] [description]
     */
    public function actionClosedService()
    {
        $user_id = Yii::$app->user->id;
        if (!$user_id) {
            $this->redirect('/site/login');
        }
        $id = Yii::$app->request->get('id', '');
        if(empty($id)){
            $this->error('参数错误');
        }
        $issue = Issue::model()->getIssue($id);
        if(!$issue){
            $this->error('工单不存在');
        }
        if($issue->assignee_id!=0 && $issue->assignee_id!=$user_id){
            $this->error('您不能取消此工单');
        }
        if($issue->current_state!==Issue::ISSUE_STATE_COMMITTED){
            $this->error('工单正在处理中，已不能取消');
        }
        $issue->content = '';
        $issue->attachment = json_encode(array());
        $issue->assignee_id = $user_id;
        $issue->modified = new CDbExpression ('NOW()');
        $issue->current_state = Issue::ISSUE_STATE_CANCEL;
        $res = $issue->save();
        if ($res) {
            $this->redirect('/issue/serviceIssue');
        }else{
            $this->error('工单取消失败');
        }
    }

//////////////////////通用////////////////////////////////////////////////

    public function actionGetAgent()
    {
        $data = array();
        $name = Yii::$app->request->get('name','');
        $model  = Agent::model()->findByAttributes(['company_name'=>$name,'status'=>'审核通过','deleted'=>'否']);
        if (empty($model)) {
            echo json_encode(array('info'=>'error','msg'=>'获取公司信息失败','data'=>$data));die;
        }
        $data['manager_name'] = isset($model->manager_name)?$model->manager_name:'';
        $data['manager_mobile'] = isset($model->manager_mobile)?$model->manager_mobile:'';
        $data['company_area'] = isset($model->company_area)?$model->company_area:'';
        echo json_encode(array('info'=>'ok','msg'=>'','data'=>$data));die;
    }

    public function actionGetUrl($issue_type)
    {
        $url = !empty($this ->issueTypeMaping[$issue_type]) ? $this ->issueTypeMaping[$issue_type] : '/issue/view';
        return $url;
    }

    /**
     * 通用查询条件
     * @param $criteria
     * @param $s
     * @param $created
     * @param $name
     * @param $issue_id
     * @return mixed
    */
    private function get_condition($query,$s,$start_date,$end_date,$name,$issue_id,$agent){
        $query->andWhere('(creator_company_id=0 OR (assignee_company_id=0 AND assignee_company_table="registrar_user"))');
        if (!empty($s)) {
            $query->andWhere("current_state='{$s}'");
        }
        if (!empty($start_date)&&!empty($end_date)) {
            $query->andWhere("created>='{$start_date} 00:00:00' AND created<='{$end_date} 23:59:59'");
        }
        if (!empty($name)) {
            $query->andWhere("name='{$name}'");
        }
        if (!empty($issue_id)) {
            $query->andWhere("id= {$issue_id}");
        }
        if(!empty($registrar)){
            $query->andWhere("creator_company_id= {$agent}");
        }
        $query->orderBy('created DESC');
        $count = $query->count();
        $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
        $model = $query->offset($pages->offset)->limit($pages->limit)->all();
        return array('model'=>$model,'pages'=>$pages);
    }

    private function get_all_agents()
    {
        $agents = Yii::$app->db->createCommand()
            ->select('id,company_name')
            ->from('agent')
            ->where("status = '审核通过' and deleted = '否'")
            ->order("id desc")
            ->queryAll();
        return $agents;
    }

    /**
     * 获取可选工单状态
     * @param $type
     * @return array
     */
    public function get_issue_type($type)
    {
        switch($type){
            case '已提交':
                return ['处理中','已完成','已关闭'];
            break;
            case '处理中':
                return ['处理中','已完成','已关闭'];
                break;
            case '已完成':
                return ['重新打开'];
                break;
            case '已关闭':
                return ['重新打开'];
                break;
            default:
               return ['已提交','处理中','已完成','已关闭','重新打开'];
            break;
        }
    }

}