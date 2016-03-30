<?php
namespace app\controllers;
use app\models\User;
use Yii;
use yii\web\Controller;
use \yii\db\Query;
use app\models\RegistryUser;
use app\models\AuthMenu;
use app\models\AuthRole;
use app\models\AuthRoleMenu;
use app\models\AuthUserRole;
use app\models\Service;
use yii\data\Pagination;
use yii\db\Expression;
use app\models\AuthMenuFunction;
use yii\db\Exception;
use app\models\ServiceOperationLog;
use app\models\Dictionary;
use app\components\Util;
use app\models\UserBelongGroup;
use app\models\Registrar;
use app\models\LoginLog;
use  app\models\UserManageScope;
use app\models\UserAgentScope;
use app\models\HelpAudit;

class UserController extends Controller
{
    /**
     * 管理员列表
     * @return [type] [description]
     */
    public function actionIndex()
    {
        $query = new Query();

        $search_username= Yii::$app->request->get('search_username','');
        $start_date= Yii::$app->request->get('start_date','');
        $end_date= Yii::$app->request->get('end_date','');

        $query->select('*')->from('vr_admin')->where('1=1');
        
        if (!empty($search_username)) {
            $query->andWhere("userName LIKE '%{$search_username}%'");
        }
        if (!empty($start_date)) {
            $query->andWhere("createTime >= '{$start_date}'");
        }
        if (!empty($end_date)) {
            $query->andWhere("createTime <= '{$end_date}'");
        }

        $query->orderBy("id desc");
        $count = $query->count();
        $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
        $query->offset($pages->offset)->limit($pages->limit);
        $users = $query->all();

        return $this->render('index',array(
            'users' => $users,
            'pages'=>$pages,
            'search_username'=>$search_username,
            'start_date'=>$start_date,
            'end_date'=>$end_date
        ));
    }

    /**
     * 添加管理员
     * @return [type] [description]
     */
    public function actionCreate()
    {
        $uid = Yii::$app->user->id;
        if (empty($uid)) {
            $this->error('请先登录','/site/login');
        }

        if(isset($_POST)&&!empty($_POST))
        {
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            /***登陆名更新***/
            $logonName =  trim(strtolower($_POST['logonName']));
            if(empty($logonName)){
               $this->error('登陆名不能为空');
            }
            $emailflag = User::findOne(['logonName'=>$logonName]);
            if($emailflag){
                $this->error('登陆名已经存在');
            }

            $password = trim($_POST['password']);
            if(!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[\s\S]*$/",$password)){
                $this->error('密码必须包含大写字母、小写字母、数字');
            }
            if(strlen($password)<6){
                $this->error('密码长度6-20位');
            }
            if ($password) {
                $password = Service::create_password($password);
            }
            $user_role = htmlspecialchars(trim($_POST['user_role']));
            if(empty($user_role)){
                $this->error('请选择用户角色');
            }
            $model = new User();
            $model->logonName = $logonName;
            $model->userName = htmlspecialchars(trim($_POST['userName']));
            $model->logonPwd = $password;
            $model->last_login_ip = Util::get_ip();
            $model->last_login_time = new Expression('NOW()');
            $model->creatTime =  new Expression('NOW()');
            $model->userRole = $user_role;
            try {

                if(!$model->save()){
                    throw new \yii\base\Exception(json_encode($model->errors,JSON_UNESCAPED_UNICODE));
                }
                if (!empty($_POST['user_role'])) {
                    $amf = new AuthUserRole();
                    $amf->user_id = $model->id;
                    $amf->role_id = htmlspecialchars(trim($_POST['user_role']));
                    $amf->created = date('Y-m-d H:i:s',time());
                    if(!$amf->save()){
                        throw new \yii\base\Exception('保存用户角色失败');
                    }
                }
                $transaction->commit();
                $this->success('添加成功');
            } catch (Exception $e) {
                $transaction->rollBack();
                $this->error($e->getMessage());
            }
        }
        $query = new Query();
        $query->select('id,name')
                ->from('vr_auth_role');
        $roles = $query->createCommand()->queryAll();
        return $this->render('create',['roles'=>$roles]);
    }

    /**
     * 编辑管理员
     * @return [type] [description]
     */
    public function actionEdit()
    {
        $uid = Yii::$app->user->id;
        if (empty($uid)) {
            $this->error('请先登录','/site/login');
        }
        $id = trim(strip_tags(Yii::$app->request->getBodyParam('id', '')));
        if(Yii::$app->request->isPost){
            $id = trim(strip_tags(Yii::$app->request->post('id', '')));
        }else{
            $id = trim(strip_tags(Yii::$app->request->get('id', '')));
        }
        $model = User::findOne(['id'=>$id]);

        if(empty($model))
        {
            $this->error('获取用户信息失败','/user/index');
        }
        $user_id = $model->id;
        $user_role = $model->user_role;

        if(isset($_POST)&&!empty($_POST))
        {
            $before_edit = json_encode($model->attributes);
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();

            if (empty($_POST['usreName'])) {
                $this->error('请填写昵称');
            }
            if (empty($_POST['user_role'])) {
                $this->error('请选择角色');
            }
            $logonName =  trim(strtolower($_POST['logonName']));
            if (empty($logonName)) {
                $this->error('请填写登陆名');
            }
            $emailflag = User::findOne(['logonName'=>$logonName]);
            if(!empty($emailflag)){
                if($emailflag->id!=trim($_POST['id'])){
                    $this->error('登陆名已经存在');
                }
            }
            $password = trim($_POST['password']);
            if ($password) {
                if(!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[\s\S]*$/",$password)){
                    $this->error('密码必须包含大写字母、小写字母、数字');
                }
                $password = Service::create_password($password);
            }else{
                $password = $model->password;
            }

            try {
                $model->logonName = $logonName;
                $model->userName = htmlspecialchars(trim($_POST['userName']));
                $model->logonPwd = $password;
                $model->last_login_ip = Util::get_ip();
                $model->last_login_time = new Expression('NOW()');
                $model->userRole = $_POST['user_role'];
                if (!$model->save()) {
                    $this->error('编辑失败');
                }

                $new_user_role = trim($_POST['user_role']);

                if (!empty($_POST['user_role']) && $new_user_role!=$user_role) {
                    $connection->createCommand()->delete("vr_auth_user_role","user_id = {$user_id}")->execute();
                    $amf = new AuthUserRole();
                    $amf->user_id = $user_id;
                    $amf->role_id = htmlspecialchars(trim($_POST['user_role']));
                    $amf->created = new Expression('NOW()');
                    $amf->save();
                }
                $transaction->commit();
                $this->success('编辑成功');
            } catch (Exception $e) {
                $transaction->rollBack();
                $this->error($e->getMessage());
            }
        }
        $query = new Query();
        $query->select('id,name')
            ->from('auth_role');
        $roles = $query->createCommand()->queryAll();
        return $this->render('edit',array('model'=>$model,'roles'=>$roles));
    }

    /**
     * 激活和锁定注册商用户
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->get('id','');

        $uid = Yii::$app->user->id;
        if(empty($uid)) $this->error('请先登录');

        $model = User::findOne(['id'=>$id]);
        if($model===null){
            $this->error('参数错误');
        }
        $user_id = $model->id;
    	$sta = $model->status == '正常' ? '删除':'正常';
      	$connection = Yii::$app->db;
      	$status = $connection->createCommand()->update('vr_admin', [
	        				'status' => $sta,
	        			], "id={$user_id}")->execute();
        if ($status>0) {
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }

    /**
     * 分配权限
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionSetting($id)
    {
        $uid= Yii::$app->user->id;
        if(empty($uid)){
            return $this->redirect('/site/login');
        }
      	$user = $this->loadModel($id);
		$query = new Query();
        $query->select('id,name')
				->from('{{%auth_role}}');
		$roles = $query->createCommand()->queryAll();
        $queryrole = new Query();

        $queryrole->select('role_id')
            ->from('{{%auth_user_role}}')
            ->where("user_id = $id");
        $user_has_role = $queryrole->createCommand()->queryColumn();

        return $this->render('setting',array('user'=>$user,'roles'=>$roles,'user_has_role'=>$user_has_role));
    }

    /**
     * 保存分配权限
     * @return [type] [description]
     */
    public function actionSave()
    {
        $uid = Yii::$app->user->id;
        if(empty($uid)){
            return $this->redirect('/site/login');
        }
        $action = '/user/index';
		if (isset($_POST['user_id'])&&!empty($_POST['user_id'])) {
			$user_id = trim($_POST['user_id']);
			$model = $this->loadModel($user_id);
			if (!empty($model)) {
                $user_role = $model->userRole;

				$connection = Yii::$app->db;
				$transaction = $connection->beginTransaction();
				try {
                    $query = new Query();
                    $query->select('role_id')
                            ->from('{{%auth_user_role}}')
                            ->where("user_id = {$user_id}");
                    $roles = $query->createCommand()->queryColumn();

                    $connection->createCommand()->delete("{{%auth_user_role}}","user_id = {$user_id}")->execute();

    			    if (!empty($_POST['role_id'])) {
		    			$amf = new AuthUserRole();
			    		$amf->user_id = $user_id;
			    		$amf->role_id = trim($_POST['role_id']);
			    		$amf->created = date('Y-m-d H:i:s',time());
			    		$amf->save();
                        $connection->createCommand()->update('{{%admin}}', [
                            'userRole' => trim($_POST['role_id'])
                        ], "id={$user_id}")->execute();
    			    }
    			    $transaction->commit();
				} catch(Exception $e) {
				    $transaction->rollBack();
				}
			}
		}
		return $this->redirect(array($action));
    }

    public function loadModel($id)
    {
        $model = User::findOne($id);
        return $model;
    }


    public function actionLoginlog()
    {
        $user_id = Yii::$app->user->id;
        if(empty($user_id)){
            return $this->redirect('/site/login');
        }
        $query = new Query();
        $query->select('*')->from('login_log')->orderBy('created DESC');
        $count = $query->count();
        $pages = new Pagination(['defaultPageSize'=>50,'totalCount'=>$count]);
        $query->offset($pages->offset)->limit($pages->limit);
        $loginlog = $query->all();
        return $this->render('loginlog',array('model'=>$loginlog,'pages'=>$pages));
    }
}
