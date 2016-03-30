<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015-8-28
 * Time: 18:50
 */

namespace app\controllers;

use app\models\AuditReasonCategory;
use yii;
use yii\web\Controller;
use \yii\db\Query;
use app\models\AuditReason;
use yii\data\Pagination;
use yii\db\Expression;
use yii\db\Exception;
use app\models\Service;
use app\models\Helper;
use yii\helpers\HtmlPurifier;
use app\models\ServiceOperationLog;

class AuditReasonController extends Controller{

    public function actionIndex(){
        $uid = Yii::$app->user->id;
        if(!empty($uid)){

            $deleted = Yii::$app->request->get('deleted','');
            $category_id = Yii::$app->request->get('category_id','');
            if(!isset($_GET['category_id']) && !isset($_GET['deleted'])){
                $deleted = 'yes';
            }
            $querylist = new Query();
            $querylist->select('r.*,c.name')->from('audit_reason r');
            if ($deleted=='yes')
            {
                $querylist->andWhere("r.deleted='否'");
            }
            if (!empty($category_id))
            {
                $querylist->andWhere("r.category_id = {$category_id}");
            }
            $querylist->join('LEFT JOIN','audit_reason_category c','c.id=r.category_id');
            $querylist->orderBy('r.created desc');

            $count = $querylist->count();
            $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
            $querylist->offset($pages->offset)->limit($pages->limit);
            $data = $querylist->all();
            //获取所有审核类型
            $category = $this->get_all_category();
            $categorys = Helper::sort_array_by_word($category);

            return $this->render('index',[
                'category_id' => $category_id,
                'deleted' =>$deleted,
                'data' => $data,
                'pages'=>$pages,
                'categorys'=>$categorys
            ]);
        }else{
            return  $this->redirect('/site/login');
        }
    }

    /**
     * 获取所有分类
     * @return array
     */
    private function get_all_category(){
        $arr = [];
        $querycategory = new Query();
        $category = $querycategory->select('name,id')->from('audit_reason_category')->where('deleted="否"')->all();
        foreach($category as $info){
            $arr[$info['id']] = $info['name'];
        }
        return $arr;
    }
    /**
     *创建/修改
     * @return string|yii\web\Response
     */
    public function actionCreate(){
        $uid = Yii::$app->user->id;
        if(!empty($uid)){

            if(Yii::$app->request->isPost){
                $title = HtmlPurifier::process(trim(Yii::$app->request->post('title','')));
                $content = HtmlPurifier::process(trim(Yii::$app->request->post('content','')));
                $category_id = HtmlPurifier::process(trim(Yii::$app->request->post('category_id','')));
                $guid = HtmlPurifier::process(trim(Yii::$app->request->post('guid','')));

                if(empty($title)){
                    $this->error('请输入标题');
                }
                if(empty($content)){
                    $this->error('请输入原因');
                }
                if(empty($category_id)){
                    $this->error('请选择原因类别');
                }
                if(empty($guid)){
                    $model = new AuditReason();
                    $model->created = new Expression('NOW()');
                    $model->guid = Service::create_guid();
                    $before = '';
                }
                //修改
                if(!empty($guid)){
                    $model = AuditReason::findOne(['guid'=>$guid]);
                    if(null==$model){
                        $this->error('审核原因不存在');
                    }
                    $before = json_encode($model->attributes);
                }
                $model->category_id = $category_id;
                $model->title = HtmlPurifier::process($title);
                $model->content = HtmlPurifier::process($content);
                $model->operator_id = $uid;
                $model->modified = new Expression('NOW()');
                if($model->save()){
                    ServiceOperationLog::create_operation_log(json_encode($model->attributes),
                        $before,'增加/修改审核原因','/audit-reason/create',$uid);
                    $this->success('操作成功');
                }else{
                    $this->error('操作失败');
                }
            }

            $model = new AuditReason();
            //修改审核原因
            $guid = Yii::$app->request->get('guid','');
            if(!empty($guid)){
                $model = AuditReason::findOne(['guid'=>$guid]);
                if(null==$model){
                    $this->error('记录不存在');
                }
            }
            //获取所有原因类型
            $category = $this->get_all_category();
            $categorys = Helper::sort_array_by_word($category);

            return $this->render('create',array(
                'model'=>$model,
                'categorys'=>$categorys
            ));
        }else{
            if(Yii::$app->request->isAjax){
                $this->error('请先登录');
            }else{
                return $this->redirect('/site/login');
            }
        }
    }

    /**
     * 删除审核原因
     * @return yii\web\Response
     */
    public function actionDelete(){
        $uid = Yii::$app->user->id;
        if(!empty($uid)){
            $guid = Yii::$app->request->get('guid','');
            if(!empty($guid)){
                $model = AuditReason::findOne(['guid'=>$guid]);
                if(null==$model){
                    $this->error('记录不存在');
                }
                $model->deleted = '是';
                $model->operator_id = $uid;
                $model->modified = new Expression('NOW()');
                if($model->save()){
                    ServiceOperationLog::create_operation_log('',
                        json_encode($model->attributes),'删除审核原因','/audit-reason/delete',$uid);
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

    /***
     * 添加/修改原因类别
     * @return string|yii\web\Response
     */
    public function actionCreateCategory(){
        $uid = Yii::$app->user->id;
        if(empty($uid)){
            $this->error('请先登录');
        }
        if(Yii::$app->request->isPost){
            $name = HtmlPurifier::process(trim(Yii::$app->request->post('name','')));
            $guid = HtmlPurifier::process(trim(Yii::$app->request->post('guid','')));
            if(empty($name)){
                $this->error('请输入标题');
            }

            $len = mb_strlen($name);

            if(1>$len || $len>30){
                $this->error('长度应该在1~30位之间 ');
            }
            $namemodel = AuditReasonCategory::findOne(['name'=>$name]);

            if(empty($guid)){
                if(null!==$namemodel){
                    $this->error('分类已存在');
                }
                $model = new AuditReasonCategory();
                $model->created = new Expression('NOW()');
                $model->guid = Service::create_guid();
                $before = '';
            }
            //修改
            if(!empty($guid)){
                if(null!==$namemodel && $guid!=$namemodel->guid){
                    $this->error('分类已存在');
                }
                $model = AuditReasonCategory::findOne(['guid'=>$guid]);
                if(null==$model){
                    $this->error('原因类别不存在');
                }
                $before = json_encode($model->attributes);
            }
            $model->name = HtmlPurifier::process($name);
            $model->operator_id = $uid;
            $model->deleted = '否';
            $model->parent_id = 0;
            $model->modified = new Expression('NOW()');
            if($model->save()){
                ServiceOperationLog::create_operation_log(
                    json_encode($model->attributes),$before,'增加/修改审核原因类别','/audit-reason/create-category',$uid);

                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }
        }
    }
    /**
     * 删除审核原因
     * @return yii\web\Response
     */
    public function actionCategoryDelete(){
        $uid = Yii::$app->user->id;
        if(!empty($uid)){
            $guid = Yii::$app->request->get('guid','');
            if(!empty($guid)){
                $model = AuditReasonCategory::findOne(['guid'=>$guid]);
                if(null==$model){
                    $this->error('记录不存在');
                }
                $reasons = AuditReason::findOne(['category_id'=>$model->id,'deleted'=>'no']);
                if(null!==$reasons){
                    $this->error('该分类下还有原因未删除，请先删除原因');
                }
                $before = json_encode($model->attributes);
                $model->deleted = '是';
                $model->operator_id = $uid;
                $model->modified = new Expression('NOW()');
                if($model->save()){
                    ServiceOperationLog::create_operation_log($before,
                        json_encode($model->attributes),'删除审核原因类别','/audit-reason/category-delete',$uid);
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

    /**
     * 审核原因列表
     * @return string
     */
    public function actionCategoryList(){
        $uid = Yii::$app->user->id;
        if(empty($uid)){
           return $this->redirect('请先登录');
        }
        $deleted = Yii::$app->request->get('deleted','');

        if(!isset($_GET['help']) && !isset($_GET['deleted'])){
            $deleted = 'yes';
        }
        $category = AuditReasonCategory::find();
        if ($deleted=='yes')
        {
            $category->andWhere("deleted='否'");
        }
        $category->orderBy('created desc');
        $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$category->count()]);
        $category->offset($pages->offset)->limit($pages->limit);
        $list = $category->all();

        return $this->render('category_list',[
            'deleted' =>$deleted,
            'data' => $list,
            'pages'=>$pages
        ]);
    }
    /**
     * 获取所有审核拒绝原因
     * @return string
     */
    public function actionGetReason(){
        $uid = Yii::$app->user->id;
        if(empty($uid)){
            echo 'err:请先登录';
            Yii::$app->end();
        }

        $type = Yii::$app->request->post('type','reject');
        $guid = Yii::$app->request->post('guid','');

        if(empty($guid)){
            echo 'err:参数错误';
            Yii::$app->end();
        }
        $count = AuditReason::find()->where("deleted='否'")->count();
        $pages = new Pagination(['defaultPageSize'=>6,'totalCount'=>$count]);
        $reasons = AuditReason::find()->where("deleted='否'")->offset($pages->offset)->limit($pages->limit)->all();
        return $this->renderAjax('_reason',['data'=>$reasons,'pages'=>$pages,'guid'=>$guid,'type'=>$type]);
    }
}