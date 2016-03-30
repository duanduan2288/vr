<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014-12-3
 * Time: 18:02
 */
namespace app\controllers;
use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use app\models\OperationLog;

class OperationLogController extends Controller{

    const ISSUE_PAGE_SIZE = 50;
    public $user_id;
    public function init(){
        $user_id = Yii::$app->user->id;
        if(empty($user_id)){
            return $this->redirect('/site/login');
        }
        $this->user_id = $user_id;
    }
    public function actionIndex()
    {
        $data = OperationLog::find()->orderBy('created DESC');
        $count = $data->count();
        $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
        $data->offset($pages->offset)->limit($pages->limit);
        $models = $data->all();

       return $this->render('index',array('model'=>$models,'pages'=>$pages));
    }

    public function actionDetail(){
        $id = Yii::$app->request->get('id','');
        if(empty($id)){
            $this->error('参数错误');
        }
        $models = OperationLog::findOne($id);
        if(null==$models){
            $this->error('数据不存在');
        }
        return $this->render('detail',array('models'=>$models));
    }
} 