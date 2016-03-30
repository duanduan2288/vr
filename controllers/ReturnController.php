<?php
namespace app\controllers;

use app\models\Blacklist;
use app\models\FeedbackState;
use app\models\Helper;
use app\models\Service;
use app\models\State;
use Yii;
use \yii\db\Query;
use yii\data\Pagination;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\helpers\HtmlPurifier;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class ReturnController extends Controller
{


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    //客户回访统计
    public function actionStatistic(){
        return $this->render('statistic');
    }
    //回访状态管理
    public function actionState(){
//        $uid = Yii::$app->user->id;
//        if(!empty($uid)){
//            $deleted = Yii::$app->request->get('deleted','');
//            if(!isset($_GET['deleted'])){
//                $deleted = 'yes';
//            }
//            $querylist = new Query();
//            $querylist->select('*')->from('state');
//
//            $querylist->orderBy('id asc');
//            $count = $querylist->count();
//            $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
//            $querylist->offset($pages->offset)->limit($pages->limit);
//            $data = $querylist->all();
//
//            return $this->render('state',[
//                'deleted' =>$deleted, 'data' => $data,'pages' => $pages
//            ]);
//        }else{
//            return  $this->redirect('/site/login');
//        }
        $uid = Yii::$app->user->id;
        if(empty($uid)){
            return $this->redirect('请先登录');
        }
        $deleted = Yii::$app->request->get('deleted','');

        if(!isset($_GET['help']) && !isset($_GET['deleted'])){
            $deleted = 'yes';
        }
        $category = FeedbackState::find();
        if ($deleted=='yes')
        {
            $category->andWhere("deleted='否'");
        }
        $category->orderBy('id asc');
        $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$category->count()]);
        $category->offset($pages->offset)->limit($pages->limit);
        $list = $category->all();

        return $this->render('state',[
            'deleted' =>$deleted,
            'data' => $list,
            'pages'=>$pages
        ]);
    }
    /*
     * 创建回访状态
     */
    public function actionStateCreate()
    {
        $uid = Yii::$app->user->id;
        if(empty($uid)){
            $this->error('请先登录');
        }
        if(Yii::$app->request->isPost){
            $status = trim(Yii::$app->request->post('review_status',''));
            $remark = trim(Yii::$app->request->post('remark',''));
            $guid = Yii::$app->request->post('guid','');
            if(empty($status)){
                $this->error('回访状态不能为空');
            }
            if(empty($remark)){
                $this->error('备注为必填项');
            }

            $len = mb_strlen($status);

            if(1>$len || $len>10){
                $this->error('长度应该在1~10位之间 ');
            }
            //$namemodel = State::findOne(['review_status'=>$status]);

            if(empty($guid)){
//                if(null!==$namemodel){
//                    $this->error('分类已存在');
//                }
                $model = new FeedbackState();
                $model->created = new Expression('NOW()');
                $model->guid = Service::create_guid();
            }
            //修改
            if(!empty($guid)){
//                if(null!==$namemodel && $guid==$namemodel->guid){
//                    $this->error('分类已存在');
//                }
                $model = FeedbackState::findOne(['guid'=>$guid]);
                if(null==$model){
                    $this->error('回访状态不存在');
                }
            }
            $model->review_status = HtmlPurifier::process($status);
            $model->remark = HtmlPurifier::process($remark);
            $model->operator_id = $uid;
            $model->deleted = '否';
            $model->modified = new Expression('NOW()');
            if($model->save()){
                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }
        }
    }

    /*
     * 删除回访状态
     */
    public function actionStateDelete()
    {
        $uid = Yii::$app->user->id;
        if(!empty($uid)){
            $guid = Yii::$app->request->get('guid','');
            if(!empty($guid)){
                $model = FeedbackState::findOne(['guid'=>$guid]);
                if(null==$model){
                    $this->error('记录不存在');
                }
                $model->deleted = '是';
                $model->modified = new Expression('NOW()');
                if($model->save()){
                    $this->success('删除成功');
                }else{
                    $this->error('删除失败');
                }
            }else{
                $this->error('缺少参数');
            }
        }else{
            return $this->redirect('/site/login');
        }
    }
    //回访权限管理
    public function actionJurisdiction()
    {
        return $this->render('jurisdiction');
    }
    //终端客户回访
    public function actionIndex()
    {
        return $this->render('index');
    }
    //回访历史
    public function actionHistory()
    {
        return $this->render('history');
    }

    //注册域名列表
    public function actionDoaminlist()
    {
        return $this->render('doaminlist');
    }

    //黑名单
    public function actionBlacklist()
    {
        $uid = Yii::$app->user->id;
        if(!empty($uid)){
            $querylist = new Query();
            $querylist->select('*')->from('blacklist');

            $querylist->orderBy('id asc');
            $count = $querylist->count();
            $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
            $querylist->offset($pages->offset)->limit($pages->limit);
            $data = $querylist->all();
            //获取所有审核类型
            //var_dump($data);die;
            return $this->render('blacklist',[
                'data' => $data,'pages' => $pages
            ]);
        }else{
            return  $this->redirect('/site/login');
        }
    }

    /**
     * 创建黑名单
     */
    public function actionBlacklistCreat()
    {
        $uid = Yii::$app->user->id;
        if (!empty($uid)) {

            if (Yii::$app->request->isPost) {
                $phone = Yii::$app->request->post('phone', '');
                $model = new Blacklist();
                $model->created = new Expression('NOW()');

                $model->phone = HtmlPurifier::process($phone);
                $model->modified = new Expression('NOW()');
                if ($model->save()) {
                    $this->success('添加成功','/return/blacklist');

                } else {
                    $this->error('操作失败');
                }
            }else{
                $model = new Blacklist();
            }
            return $this->render('blacklistcreat' ,['model' => $model]);
        }
    }

    /**
     * 修改黑名单
     */
    public function actionBlacklistEdit()
    {
        $id = intval($_REQUEST['id']);

        if (!empty($id)) {
            $model = Blacklist::findOne(['id' => $id]);
            if (null == $model) {
                $this->error('记录不存在');
            }
        }
        if (Yii::$app->request->isPost) {
            $phone = Yii::$app->request->post('phone', '');
            $model->phone = HtmlPurifier::process($phone);
            $model->modified = new Expression('NOW()');
            if ($model->save()) {
                $this->success('修改成功', '/return/blacklist');
            } else {
                $this->error('修改失败');
            }
        }

        return $this->render('blacklistedit', ['model' => $model]);

    }

    /**
     * 删除黑名单
     *
     */
    public function actionBlacklistDelete()
    {
        $uid = Yii::$app->user->id;
        if(!empty($uid)){
            $id = Yii::$app->request->get('id','');
            if(!empty($id)){
                $model = Blacklist::deleteAll(['id'=>$id]);
                if(null==$model){
                    $this->success('记录已删除');
                }
            }else{
                $this->error('缺少参数');
            }
        }else{
            return $this->redirect('/site/login');
        }
    }

}
