<?php
namespace app\controllers;
use Yii;
use yii\web\Controller;
use \yii\db\Query;
use app\models\AuthFunction;
use yii\data\Pagination;
use yii\db\Expression;
use yii\db\Exception;
use app\models\ServiceOperationLog;

class FunctionController extends Controller
{
    /**
     * function列表
     * @return [type] [description]
     */
    public function actionIndex()
    {
        $uid = Yii::$app->user->id;
        if(!empty($uid)){

            $search_controller=(isset($_GET['search_controller']))?$_GET['search_controller']:'';
            $search_action=(isset($_GET['search_action']))?$_GET['search_action']:'';

            $querylist = new Query();
            $querylist->select('*')->from('{{%auth_function}}');
            $querylist->where("1=1");
            if (!empty($search_controller)) {
                $querylist->andWhere("controller LIKE '%{$search_controller}%'");
            }
            if (!empty($search_action)) {
                $querylist->andWhere("action LIKE '%{$search_action}%'");
            }

            $querylist->orderBy("id desc");
            $count = $querylist->count();
            $pages = new Pagination(['defaultPageSize'=>1000,'totalCount'=>$count]);
            $querylist->offset($pages->offset)->limit($pages->limit);
            $functions = $querylist->all();

           return $this->render('index',[
                'functions' => $functions,
                'pages'=>$pages,
                'search_controller'=>$search_controller,
                'search_action'=>$search_action,
            ]);
        }else{
         return $this->redirect('/site/login');
        }
    }

    /**
     * 添加和修改function
     * @return [type] [description]
     */
    public function actionCreate()
    {
        $uid = Yii::$app->user->id;
        if(!empty($uid)){
            $id = '';
            if(isset($_REQUEST['id'])&&!empty($_REQUEST['id']))
            {
                $id = intval($_REQUEST['id']);
                $model = $this->loadModel($id);
            }

            if(empty($model))
            {
                $model = new AuthFunction;
            }
            if(isset($_POST['Fun']))
            {
                $before_edit = $id != '' ? json_encode($model->attributes) : '';
                $model->attributes = $_POST['Fun'];
                $model->created = new Expression('NOW()');
                $model->modified = new Expression('NOW()');
                if($model->save())
                {
                    return $this->redirect(array('/function/index'));
                }
            }
            return $this->render('create',['model'=>$model]);
        }else{
          return $this->redirect('/site/login');
        }
    }

    /**
     * 删除
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionDelete($id)
    {
        $uid = Yii::$app->user->id;
        if(!empty($uid)){
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $info = $this->loadModel($id);
                $connection->createCommand()->delete("{{%auth_function}}", "id={$id}")->execute();
                $connection->createCommand()->delete("{{%auth_menu_function}}", "function_id={$id}")->execute();

                $transaction->commit();
            } catch(Exception $e) {
                $transaction->rollBack();
            }
            return $this->redirect(array('/function/index'));
        }else{
          return $this->redirect('/site/login');
        }
    }

    public function loadModel($id)
    {
        $model = AuthFunction::findOne($id);
        return $model;
    }

}
