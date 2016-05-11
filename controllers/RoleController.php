<?php
namespace app\controllers;
use app\components\BaseController;
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

class RoleController extends BaseController
{
    public function actionIndex()
    {
        $uid = Yii::$app->user->id;
        if(!empty($uid)){

            $search_name=(isset($_GET['search_name']))?$_GET['search_name']:'';

            $querylist = new Query();
            $querylist->select('*')->from('{{%auth_role}}');
            $querylist->where("1=1");

            if (!empty($search_name)) {
                $querylist->andWhere("name LIKE '%{$search_name}%'");
            }
            $querylist->orderBy("id desc");
            $count = $querylist->count();
            $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
            $querylist->offset($pages->offset)->limit($pages->limit);
            $roles = $querylist->all();

            return $this->render('index',array(
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
            // 所有菜单
            $query = new Query();

            $query->select('id,name,parent_id')
                    ->from('{{%auth_menu}}')
                    ->where("deleted = '否'")
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
                            ->from('{{%auth_role_menu}}')
                            ->where("role_id = $id");
                $role_has_menus = $rolequery->createCommand()->queryColumn();
            }
            if(empty($model))
            {
                $model = new AuthRole;
            }
            return $this->render('create',array('model'=>$model,'menus'=>$menus,'role_has_menus'=>$role_has_menus));
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

                if (!empty($role_id)) {

                    $model = AuthRole::findOne(['id'=>$role_id]);
                    if(null===$model){
                        echo json_encode(array('info'=>'error','msg'=>'角色中文或英文名称已存在','data'=>array()));
                        Yii::$app->end();
                    }
                    $info = AuthRole::find()->where("name = '{$role_name}' and id != {$role_id}")->one();
                    if ($info) {
                        echo json_encode(array('info'=>'error','msg'=>'角色中文或英文名称已存在','data'=>array()));
                        Yii::$app->end();
                    }
                    $msg = '编辑成功';
                } else {

                    $info = AuthRole::find()->where("name = '{$role_name}'")->one();
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
                $model->type = '系统创建';
                $model->creator = Yii::$app->user->id;
                $model->created = new Expression('NOW()');

                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
                    if ($role_id) {
                        $roles = AuthRole::findOne(['id'=>$role_id]);
                        $connection->createCommand()->delete("{{%auth_role_menu}}","role_id={$role_id}")->execute();
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
                $connection->createCommand()->delete("{{%auth_role}}", "id={$id}")->execute();
                $connection->createCommand()->delete("{{%auth_role_menu}}", "role_id={$id}")->execute();
                $connection->createCommand()->delete("{{%auth_user_role}}", "role_id={$id}")->execute();

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

            $role_info = AuthRole::findOne($id);
            $query = new Query();
            $query->select('user_id')
                    ->from('{{%auth_user_role}}')
                    ->where("role_id = '{$id}'");

            $user_ids = $query->createCommand()->queryColumn();
            $uids = !empty($user_ids)?implode(',', $user_ids):'';

            $querylist = new Query();
            $querylist  ->select('*')
                        ->from('{{%admin}}')
                        ->where(!empty($uids)?"id in ($uids)":"1=2")
                        ->orderBy("id desc");

            $count = $querylist->count();
            $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
            $querylist->offset($pages->offset)->limit($pages->limit);
            $users = $querylist->all();

            return $this->render('role_user',array(
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
                ->from('{{%auth_menu}}')
                ->where("deleted = '否'")
                ->orderBy("weight asc");

        $menus = $query->createCommand()->queryAll();

        $querylist = new Query();

        $querylist->select('menu_id')
                    ->from('{{%auth_role_menu}}')
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
                                ->from('{{%auth_role_menu}}')
                                ->where("role_id = {$role_id}")
                                ->orderBy("weight asc");
                        $menus = $query->createCommand()->queryColumn();

                        $connection->createCommand()->delete("{{%auth_role_menu}}","role_id={$role_id}")->execute();

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
