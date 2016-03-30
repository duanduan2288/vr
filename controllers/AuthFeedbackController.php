<?php

    /**
     * Created by duan.
     * User: duan
     * Date: 2015-9-21
     * Time: 15:33
     */
    namespace app\controllers;
    use app\models\Agent;
    use app\models\HelpAudit;
    use app\models\Helper;
    use app\models\UserAgentScope;
    use Yii;
    use yii\web\Controller;
    use yii\db\Query;
    use yii\data\Pagination;
    use app\models\Service;
    use app\models\RegistryUser;
    use app\components\Util;
    use yii\db\Expression;
    use app\models\UserManageScope;
    use app\models\ServiceOperationLog;

    class AuthFeedbackController extends Controller
    {
        /**
         * 所有回访人员
         * @return [type] [description]
         */
        public function actionUserlist()
        {
            $uid = Yii::$app->user->id;
            if(empty($uid)){
                $this->error('请先登录');
            }
            $query = new Query();

            $search_username=(isset($_GET['search_username']))?$_GET['search_username']:'';
            $search_email=strtolower((isset($_GET['search_email']))?$_GET['search_email']:'');

            $query->select('r.*')->from('registry_user r')->where('1=1');

            if (!empty($search_username)) {
                $query->andWhere("(r.last_name LIKE '%{$search_username}%' or r.first_name LIKE '%{$search_username}%')");
            }
            if (!empty($search_email)) {
                $query->andWhere("r.email LIKE '%{$search_email}%'");
            }
            $query->leftJoin('auth_user_role a','a.user_id=r.id');
            $query->andWhere("a.role_id=37");
            $query->orderBy("r.id desc");
            $count = $query->count();
            $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
            $query->offset($pages->offset)->limit($pages->limit);
            $users = $query->all();

            return $this->render('userlist',array(
                'users' => $users,
                'pages'=>$pages,
                'search_username'=>$search_username,
                'search_email'=>$search_email
            ));
        }

        /**
         * 管理范围
         */
        public function actionAgent(){
            $uid = Yii::$app->user->id;
            if(empty($uid)){
                return $this->redirect('/site/login');
            }
            $user_id = Yii::$app->request->post('user_id','');
            $id = Yii::$app->request->get('id','');

            $user_id = empty($id)?$user_id:$id;
            if(empty($user_id)){
                $this->error('请选择用户');
            }

            $userinfo = RegistryUser::findOne(['guid'=>$user_id]);
            if(null==$userinfo){
                $this->error('用户不存在');
            }
            if('注册商'==$userinfo->manage_type){
                $this->error('请先将用户管理方式切换为按代理商管理');
            }
            //用户已管理代理商
            $employee_agents = Service::get_agent_id_by_user_id($userinfo->id);

            if(Yii::$app->request->isPost){

                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
                    $agent_codes = Yii::$app->request->post('agent_codes',[]);
                    $temp_ids = implode('\',\'',array_unique($agent_codes));

                    //删除用户之前的管理范围
                    $connection->createCommand()->delete("user_agent_scope","user_id = {$userinfo->id} and agent_code not in ( '{$temp_ids}' )")->execute();
                    //添加管理范围
                    foreach($agent_codes as $agent_code){
                        if(in_array($agent_code,$employee_agents['a'])) continue;

                        $usermanage = new UserAgentScope();
                        $usermanage->user_id = $userinfo->id;
                        $usermanage->agent_code = $agent_code;
                        $usermanage->creator = $uid;
                        $usermanage->creator_ip = Util::get_ip();
                        $usermanage->created = new Expression('NOW()');
                        $flag = $usermanage->save();
                        if(!$flag){
                            throw new Exception(json_encode($usermanage->errors,JSON_UNESCAPED_UNICODE));
                        }
                    }
                    ServiceOperationLog::create_operation_log(json_encode($agent_codes),
                        json_encode($employee_agents['a']),'修改回访管理范围','/audit-feedback/agent',$uid);

                    $transaction->commit();
                    $this->success('操作成功');
                } catch (Exception $e) {
                    $transaction->rollBack();
                    $this->error($e->getMessage());
                }
            }else{
                //所有代理商
                $registrars = HelpAudit::get_all_agents($employee_agents['s']);
                return $this->render('agentscope',
                    [
                        'user'=>$userinfo,
                        'registrars'=>$registrars,
                        'employee_agents'=>$employee_agents['a'],
                    ]);
            }
        }
        /**
         * 管理范围
         */
        public function actionRegistrar(){
            $uid = Yii::$app->user->id;
            if(empty($uid)){
                return $this->redirect('/site/login');
            }
            $user_id = Yii::$app->request->post('user_id','');
            $id = Yii::$app->request->get('id','');

            $user_id = empty($id)?$user_id:$id;
            if(empty($user_id)){
                $this->error('请选择用户');
            }

            $userinfo = RegistryUser::findOne(['guid'=>$user_id]);
            if(null==$userinfo){
                $this->error('用户不存在');
            }
            if('代理商'==$userinfo->manage_type){
                $this->error('请先将用户管理方式切换为按注册商管理');
            }
            //用户已管理注册商
            $employee_agents = Service::get_registrar_id_by_user_id($userinfo->id);

            if(Yii::$app->request->isPost){

                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
                    $registrar_ids = Yii::$app->request->post('registrar_ids',[]);
                    $temp_ids = implode(',', $registrar_ids);
                    //删除用户之前的管理范围
                    $connection->createCommand()->delete("user_manage_scope","user_id = {$userinfo->id} and registrar_id not in ( {$temp_ids} )")->execute();
                    //添加管理范围
                    foreach($registrar_ids as $registrar_id){
                        if(in_array($registrar_id,$employee_agents['a'])) continue;

                        $usermanage = new UserManageScope();
                        $usermanage->user_id = $userinfo->id;
                        $usermanage->registrar_id = $registrar_id;
                        $usermanage->creator = $uid;
                        $usermanage->creator_ip = Util::get_ip();
                        $usermanage->created = new Expression('NOW()');
                        $flag = $usermanage->save();
                        if(!$flag){
                            throw new Exception(json_encode($usermanage->errors,JSON_UNESCAPED_UNICODE));
                        }
                    }
                    ServiceOperationLog::create_operation_log(json_encode($registrar_ids),
                        json_encode($employee_agents['a']),'修改回访管理范围','/audit-feedback/registrar',$uid);
                    $transaction->commit();
                    $this->success('操作成功');
                } catch (Exception $e) {
                    $transaction->rollBack();
                    $this->error($e->getMessage());
                }
            }else{
                //所有注册商
                $idsquery = new Query();
                $idsquery->select('id')
                    ->from('registrar')
                    ->where("id not in ( {$employee_agents['s']} ) and status = '正常' and deleted = '否'");

                $registrars = $idsquery->createCommand()->queryColumn();
                return $this->render('registrarscope',
                    [
                        'user'=>$userinfo,
                        'registrars'=>$registrars,
                        'employee_agents'=>$employee_agents['a'],
                    ]);
            }
        }

        public function actionSetting(){
            $uid = Yii::$app->user->id;
            if(empty($uid)){
                return $this->redirect('/site/login');
            }
            $user_id = Yii::$app->request->post('user_id','');
            $id = Yii::$app->request->get('id','');

            $user_id = empty($id)?$user_id:$id;
            if(empty($user_id)){
                $this->error('请选择用户');
            }

            $userinfo = RegistryUser::findOne(['guid'=>$user_id]);
            if(null==$userinfo){
                $this->error('用户不存在');
            }

            if(Yii::$app->request->isPost){
                $manage_type = trim(Yii::$app->request->post('manage_type',''));
                if(empty($manage_type)){
                    $this->error('请选择管理方式');
                }
                if($manage_type==$userinfo->manage_type){
                    $this->success('未做修改');
                }
                $before = json_encode($userinfo->attributes);
                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
                    //删除用户之前的管理范围
                    if('代理商方式'==$userinfo->manage_type){
                        $connection->createCommand()->delete("user_agent_scope","user_id = {$userinfo->id}")->execute();
                    }else{
                        $connection->createCommand()->delete("user_manage_scope","user_id = {$userinfo->id}")->execute();
                    }
                    //添加管理范围
                    if('代理商方式'==$manage_type){
                        $this->save_user_agent_scope($userinfo->id);
                    }else{
                        $this->save_user_scope($userinfo->id);
                    }
                    $userinfo->manage_type = $manage_type;
                    $userinfo->modified = new Expression('NOW()');
                    $flag = $userinfo->save();
                     if(!$flag){
                         throw new Exception(json_encode($userinfo->errors,JSON_UNESCAPED_UNICODE));
                     }
                    ServiceOperationLog::create_operation_log(json_encode($userinfo->attributes),
                        $before,'修改回访管理方式','/audit-feedback/setting',$uid);

                    $transaction->commit();
                    $this->success('操作成功','/auth-feedback/userlist');
                } catch (Exception $e) {
                    $transaction->rollBack();
                    $this->error($e->getMessage());
                }
            }else{
                $array = Helper::sort_array_by_word(['代理商方式','注册商方式']);
                return $this->render('setting',
                    [
                        'user'=>$userinfo,
                        'types'=>$array
                    ]);
            }
        }

        /**
         * 默认给审核员分配所有注册商
         * @param $user_id
         * @throws \yii\base\Exception
         */
        private function save_user_scope($user_id){
            //删除用户之前的管理范围
            Yii::$app->db->createCommand()->delete("user_manage_scope","user_id = {$user_id}")->execute();
            //获取所有注册商
            $idsquery = new Query();
            $idsquery->select('id')
                ->from('registrar')
                ->where("status = '正常' and deleted = '否'");
            $registrars = $idsquery->createCommand()->queryColumn();
            foreach($registrars as $registrar_id){
                $user_manage_scope = new UserManageScope();
                $user_manage_scope->user_id = $user_id;
                $user_manage_scope->registrar_id = $registrar_id;
                $user_manage_scope->creator = Yii::$app->user->id;
                $user_manage_scope->creator_ip = Util::get_ip();
                $user_manage_scope->created = new Expression('NOW()');
                if(!$user_manage_scope->save()){
                    throw new \yii\base\Exception('保存用户管理范围失败');
                }
            }


        }
        /**
         * 默认给回访人员分配所有代理商
         * @param $user_id
         * @throws \yii\base\Exception
         */
        private function save_user_agent_scope($user_id){
            //删除用户之前的管理范围
            Yii::$app->db->createCommand()->delete("user_agent_scope","user_id = {$user_id}")->execute();
            //所有代理商
            $registrars = HelpAudit::get_all_agents();
            foreach($registrars as $agent_code){
                if(!$agent_code) continue;
                $user_manage_scope = new UserAgentScope();
                $user_manage_scope->user_id = $user_id;
                $user_manage_scope->agent_code = $agent_code;
                $user_manage_scope->creator = Yii::$app->user->id;
                $user_manage_scope->creator_ip = Util::get_ip();
                $user_manage_scope->created = new Expression('NOW()');
                if(!$user_manage_scope->save()){
                    throw new \yii\base\Exception('保存用户管理范围失败');
                }
            }


        }
    }