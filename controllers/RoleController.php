<?php
namespace app\controllers;
use yii;
use \yii\web\Controller;
use \yii\db\Query;
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

class RoleController extends Controller
{
    public function actionIndex()
    {
        $uid = Yii::$app->user->id;
        if(!empty($uid)){

            $platform=(isset($_GET['type'])&&$_GET['type']=='2')?'注册商':'注册局';
            $type=(isset($_GET['type'])&&$_GET['type']=='2')?'2':'1';
            // $search_platform=(isset($_GET['search_platform']))?$_GET['search_platform']:'';
            $search_name=(isset($_GET['search_name']))?$_GET['search_name']:'';

            $querylist = new Query();
            $querylist->select('*')->from('auth_role');
            $querylist->where("platform = '{$platform}'");

            if (!empty($search_name)) {
                $querylist->andWhere("name LIKE '%{$search_name}%'");
            }
            if ($type == '2') {
                $querylist->andWhere("type = '系统创建'");
            }
            $querylist->orderBy("id desc");

            $count = $querylist->count();
            $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
            $querylist->offset($pages->offset)->limit($pages->limit);
            $roles = $querylist->all();

            return $this->render('index',array(
                'platform' => $platform,
                'type' => $type,
                'roles' => $roles,
                'pages'=>$pages,
                'search_name'=>$search_name
            ));
        }else{
          return $this->redirect('/site/login');
        }
    }

    public function actionCreate()
    {
        $uid = Yii::$app->user->id;
        if (!empty($uid))
        {
            $id = '';
            $menus = $role_has_menus = array();
            $platform=(isset($_GET['type'])&&$_GET['type']=='2')?'注册商':'注册局';
            $type=(isset($_GET['type'])&&$_GET['type']=='2')?'2':'1';
            // 所有菜单
            $query = new Query();

            $query->select('id,name,parent_id')
                    ->from('auth_menu')
                    ->where("platform = '{$platform}' and deleted = '否'")
                    ->orderBy("weight asc");
            $menus = $query->createCommand()->queryAll();

            if(isset($_REQUEST['id'])&&!empty($_REQUEST['id']))
            {
                $id = intval($_REQUEST['id']);
                $model = $this->loadModel($id);
                if (empty($model))
                {
                    $this->error('角色不存在');
                    Yii::$app->end();
                }
                $rolequery = new Query();
                $rolequery  ->select('menu_id')
                            ->from('auth_role_menu')
                            ->where("role_id = $id");
                $role_has_menus = $rolequery->createCommand()->queryColumn();
            }
            if(empty($model))
            {
                $model = new AuthRole;
            }
            return $this->render('create',array('model'=>$model,'menus'=>$menus,'role_has_menus'=>$role_has_menus,'platform'=>$platform,'type'=>$type));
        }
        else
        {
           return $this->redirect('/site/login');
        }
    }

    public function actionSave()
    {
        $uid = Yii::$app->user->id;
        if ($uid > 0) {
            if (isset($_POST)&&!empty($_POST))
            {
                $role_id = Yii::$app->request->post('role_id', '');
                $role_name = htmlspecialchars(Yii::$app->request->post('role_name', ''));
                $role_name_en = htmlspecialchars(Yii::$app->request->post('role_name_en', ''));
                $role_menu = Yii::$app->request->post('role_menu', '');
                $role_platform = Yii::$app->request->post('role_platform', '');

                if (!empty($role_id)) {

                    $model = AuthRole::findOne(['id'=>$role_id]);
                    if(null===$model){
                        echo json_encode(array('info'=>'error','msg'=>'角色中文或英文名称已存在','data'=>array()));
                        Yii::$app->end();
                    }
                    $info = AuthRole::find()->where("name = '{$role_name}' and id != {$role_id} and platform = '{$role_platform}' and registrar_id = 0")->one();
                    if ($info) {
                        echo json_encode(array('info'=>'error','msg'=>'角色中文或英文名称已存在','data'=>array()));
                        Yii::$app->end();
                    }
                    $msg = '编辑成功';
                } else {

                    $info = AuthRole::find()->where("name = '{$role_name}' and platform = '{$role_platform}' and registrar_id = 0")->one();
                    if ($info) {
                        echo json_encode(array('info'=>'error','msg'=>'角色中文或英文名称已存在1','data'=>array()));
                        Yii::$app->end();
                    }
                    $model = new AuthRole;
                    $model->guid = Service::create_guid();

                    $msg = '添加成功';
                }
                $model->name = $role_name;
                $model->name_en = $role_name_en;
                $model->platform = $role_platform;
                $model->type = '系统创建';
                $model->registrar_id = 0;
                $model->creator = Yii::$app->user->id;
                $model->created = new Expression('NOW()');

                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
                    if ($role_id) {
                        $roles = AuthRole::findOne(['id'=>$role_id]);
                        $connection->createCommand()->delete("auth_role_menu","role_id={$role_id}")->execute();
                        //记录操作日志
                        ServiceOperationLog::create_operation_log(json_encode($model->attributes),
                            json_encode($roles->attributes),'修改角色','/role/create',$uid);

                    }else{
                        //记录操作日志
                        ServiceOperationLog::create_operation_log(json_encode($model->attributes),
                            '','添加角色','/role/create',$uid);
                    }
                    $model->save();
                    $r_id = $role_id ? $role_id : $model->id;
                    // $role_id = $model->id;
                    if (!empty($role_menu)) {
                        foreach ($role_menu as $key => $value) {
                                $amf = new AuthRoleMenu();
                                $amf->role_id = $r_id;
                                $amf->menu_id = $value;
                                $amf->save();
                        }
                    }
                    $transaction->commit();
                    echo json_encode(array('info'=>'ok','msg'=>$msg,'data'=>array()));
                    Yii::$app->end();
                } catch(Exception $e) {
                    $transaction->rollBack();
                    echo json_encode(array('info'=>'error','msg'=>$e->getMessage(),'data'=>array()));
                    Yii::$app->end();
                }
            }else{
                echo json_encode(array('info'=>'error','msg'=>'非法提交数据','data'=>array()));
                Yii::$app->end();
            }
        }else{
            echo json_encode(array('info'=>'error','msg'=>'请先登录','data'=>array()));
            Yii::$app->end();
        }
    }


    public function actionDelete($id)
    {
        $uid = Yii::$app->user->id;
        if(!empty($uid)){
            $flag = AuthUserRole::findOne("role_id = {$id}");
            if (!empty($flag)) {
                $this->error('该角色下还存在用户，请修改用户权限后再删除');
                Yii::$app->end();
            }
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $info = $this->loadModel($id);
                $connection->createCommand()->delete("auth_role", "id={$id}")->execute();
                $connection->createCommand()->delete("auth_role_menu", "role_id={$id}")->execute();
                $connection->createCommand()->delete("auth_user_role", "role_id={$id}")->execute();

                //记录操作日志
                ServiceOperationLog::create_operation_log(json_encode('',$info->attributes),'删除角色','/role/delete',$uid);

                $transaction->commit();
            } catch(Exception $e) {
                $transaction->rollBack();
                $this->error($e->getMessage());
            }
            return $this->redirect(array('/role/index'));
        }else{
          return $this->redirect('/site/login');
        }
    }

    /**
     * 角色下的所有用户
     * @return [type] [description]
     */
    public function actionRole_user()
    {
        $uid = Yii::$app->user->id;
        if(!empty($uid)){
            $id = Yii::$app->request->getQueryParam('id','');
            $platform = (isset($_GET['type'])&&$_GET['type']=='2')?'注册商':'注册局';
            $type = (isset($_GET['type'])&&$_GET['type']=='2')?'2':'1';
            $table = (isset($_GET['type'])&&$_GET['type']=='2')?'registrar_user':'registry_user';
            $role_info = AuthRole::findOne($id);
            $query = new Query();
            $query->select('user_id')
                    ->from('auth_user_role')
                    ->where("role_id = '{$id}'");

            $user_ids = $query->createCommand()->queryColumn();
            $uids = !empty($user_ids)?implode(',', $user_ids):'';

            $querylist = new Query();
            $querylist  ->select('*')
                        ->from($table)
                        ->where(!empty($uids)?"id in ($uids)":"1=2")
                        ->orderBy("id desc");

            $count = $querylist->count();
            $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
            $querylist->offset($pages->offset)->limit($pages->limit);
            $users = $querylist->all();

            return $this->render('role_user',array(
                'platform' => $platform,
                'type' => $type,
                'role_info' => $role_info,
                'users' => $users,
                'pages'=>$pages
            ));
        }else{
            return $this->redirect('/site/login');
        }
    }

    /**
     * 暂无用
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionSetting($id)
    {
      	$role = $this->loadModel($id);
      	// 所有菜单
        $query  = new Query();
        $query->select('id,name,parent_id')
                ->from('auth_menu')
                ->where("platform = '{$role->platform}' and deleted = '否'")
                ->orderBy("weight asc");

        $menus = $query->createCommand()->queryAll();

        $querylist = new Query();

        $querylist->select('menu_id')
                    ->from('auth_role_menu')
                    ->where("role_id = $id");
        $role_has_menus = $querylist->createCommand()->queryColumn();

        return $this->render('setting',array('role'=>$role,'menus'=>$menus,'role_has_menus'=>$role_has_menus));
    }

    /**
     * 暂无用
     * @return [type] [description]
     */
    public function actionSave1()
    {
        $uid = Yii::$app->user->id;
        if ($uid > 0) {
            if (isset($_POST)&&!empty($_POST))
            {
                $role_id = Yii::$app->request->post('role_id', '');
                $role_menu = Yii::$app->request->post('role_menu', '');
                $model = $this->loadModel($role_id);
                if (!empty($model)) {
                    $connection = Yii::$app->db;
                    $transaction = $connection->beginTransaction();
                    try {
                        $query = new Query();
                        $query->select('menu_id')
                                ->from('auth_role_menu')
                                ->where("role_id = {$role_id}")
                                ->orderBy("weight asc");
                        $menus = $query->createCommand()->queryColumn();

                        $connection->createCommand()->delete("auth_role_menu","role_id={$role_id}")->execute();
                        //记录操作日志
                        ServiceOperationLog::create_operation_log(json_encode($role_menu),json_encode($menus),'为角色分配菜单','/role/setting',$uid);

                        if (!empty($role_menu)) {
                            foreach ($role_menu as $key => $value) {
                                    $amf = new AuthRoleMenu();
                                    $amf->role_id = $role_id;
                                    $amf->menu_id = $value;
                                    $amf->save();
                            }
                        }
                        $transaction->commit();
                        echo json_encode(array('info'=>'ok','msg'=>'分配菜单成功','data'=>array()));
                    } catch(Exception $e) {
                        $transaction->rollBack();
                        echo json_encode(array('info'=>'error','msg'=>'分配菜单失败','data'=>array()));
                    }
                }else{
                    echo json_encode(array('info'=>'error','msg'=>'角色不存在','data'=>array()));
                }
            }else{
                echo json_encode(array('info'=>'error','msg'=>'非法提交数据','data'=>array()));
            }
        }else{
            echo json_encode(array('info'=>'error','msg'=>'请先登录','data'=>array()));
        }
    }

    public function loadModel($id)
    {
        $model = AuthRole::findOne($id);
         if($model===null)
             throw new Exception('记录不存在');
        return $model;
    }

}
