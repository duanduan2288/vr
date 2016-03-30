<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015-8-31
 * Time: 15:54
 */

namespace app\controllers;
use app\models\Helper;
use app\models\UserManageScope;
use Yii;
use yii\web\Controller;
use app\models\RegistryUser;
use yii\db\Expression;
use app\models\Service;
use yii\db\Query;
use yii\base\Exception;
use app\components\Util;
use yii\data\Pagination;
use app\models\ServiceOperationLog;

class AuditAuthController extends Controller{

    /**
     * 所有审核员
     * @return [type] [description]
     */
    public function actionRegistry_list()
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
        $query->andWhere("a.role_id=34");
        $query->orderBy("r.id desc");
        $count = $query->count();
        $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
        $query->offset($pages->offset)->limit($pages->limit);
        $users = $query->all();

        return $this->render('registry_list',array(
            'users' => $users,
            'pages'=>$pages,
            'search_username'=>$search_username,
            'search_email'=>$search_email
        ));
    }

    /**
     * 管理范围
     */
    public function actionScope(){
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
                    json_encode($employee_agents['a']),'修改管理范围','/audit-auth/scope',$uid);

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

            $roleUsers = RegistryUser::findAll('1=1');

            return $this->render('scope',
                [
                    'user'=>$userinfo,
                    'roleUsers'=>$roleUsers,
                    'registrars'=>$registrars,
                    'employee_agents'=>$employee_agents['a'],
                ]);
        }
    }
    /**
     * 调整审核权限
     */
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
        //用户已有权限
        $audit_scopes = $userinfo->audit_scope;
        $before = json_encode($userinfo->attributes);

        if(Yii::$app->request->isPost){
            $audit_scope = Yii::$app->request->post('audit_scopes',[]);
            $userinfo->audit_scope = json_encode($audit_scope);
            $userinfo->modified = new Expression('NOW()');
            $userinfo->operator_id = $uid;
            if($userinfo->save()){
                ServiceOperationLog::create_operation_log(json_encode($userinfo->attributes),
                    json_encode($before),'修改管理范围','/audit-auth/scope',$uid);

                $this->success('操作成功','/audit-auth/registry_list');
            }else{
                $this->error('操作失败');
            }
        }else{
            //获取所有审核类型
            $types = Helper::sort_array_by_word(Helper::get_enum_value('audit_data','audit_category'));

            return $this->render('setting',
                [
                    'user'=>$userinfo,
                    'types'=>$types,
                    'scopes'=>empty($audit_scopes)?[]:json_decode($audit_scopes)
                ]);
        }
    }
}