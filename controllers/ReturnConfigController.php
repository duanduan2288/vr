<?php
namespace app\controllers;
use app\models\ReviewConfig;
use yii;
use yii\web\Controller;
use \yii\db\Query;
use yii\data\Pagination;
use yii\db\Expression;
use yii\base\Exception;
use yii\helpers\HtmlPurifier;
use app\models\AuditData;
use app\extensions\epp\EPP;
use app\models\Issue;
use app\extensions\domain\RegAuthGreenChannel;
use app\models\EppOperationLog;
use app\models\Service;
use yii\helpers\Url;

class ReturnConfigController extends Controller
{
    protected $uid;
    public function init()
    {
        $uid = Yii::$app->user->id;

        if(!empty($uid)){
            $this->uid = $uid;
        }else{
            if(Yii::$app->request->isAjax){
                $this->error('请先登录');
            }else{
                return $this->redirect('/site/login');
            }
        }
    }
    public function actionIndex()
    {
                $querylist = new Query();
                $querylist->select('*')->from('review_config');

                $querylist->orderBy('id asc');
                $count = $querylist->count();
                $pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
                $querylist->offset($pages->offset)->limit($pages->limit);
                $data = $querylist->all();

                return $this->render('allocation',[
                    'data' => $data,'pages' => $pages
        ]);


        //return $this->render('allocation');
    }

    /**
     * @return string
     * 回访管理配置创建与删除
     */
    public function actionCreate()
    {
        if(Yii::$app->request->isPost){
            $strategy = Yii::$app->request->post('strategy','');
            $detail = trim(Yii::$app->request->post('detail',''));
            $treatment = Yii::$app->request->post('treatment','');
            $status = Yii::$app->request->post('status','');
            $guid = Yii::$app->request->post('guid','');

            if(empty($strategy)){
                $this->error('策略不能为空');
            }
            if(empty($detail)){
                $this->error('规则详情不能为空');
            }
            if(empty($treatment)){
                $this->error('处理方式不能为空');
            }
            if(empty($status)){
                $this->error('状态为必填项');
            }
                $model = new ReviewConfig();
                $model->created = new Expression('NOW()');
                $model->guid = Service::create_guid();

            //修改
            if(!empty($guid)){
//                if(null!==$namemodel && $guid==$namemodel->guid){
//                    $this->error('分类已存在');
//                }
                $model = ReviewConfig::findOne(['guid'=>$guid]);

                if(null==$model){
                    $this->error('回访状态不存在');
                }
            }
            $model->strategy = HtmlPurifier::process($strategy);
            $model->detail = HtmlPurifier::process($detail);
            $model->treatment = HtmlPurifier::process($treatment);
            $model->status = HtmlPurifier::process($status);
            $model->operator_id = $this->uid;
            $model->modified = new Expression('NOW()');
            if($model->save()){
                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }

        }
        return $this->render('allocation');
    }

    public function actionDelete()
    {
            $id = Yii::$app->request->get('id','');
            if(!empty($id)){
                $model = ReviewConfig::findOne($id);

                if(null==$model){
                    $this->error('记录不存在');
                }
                    if($model->status == '无效') {
                        $status = '有效';
                    }else{
                        $status = '无效';
                    }

                    $model->status=$status;
                    $model->modified = new Expression('NOW()');
                    if($model->save()){
                        $this->success('设置成功');
                    }else{
                        $this->error('设置失败');
                    }
            }else{
                $this->error('缺少参数');
            }
        //return $this->render('allocation');
    }
//    public function actionEdit()
//    {
//        return $this->render('allocation');
//    }
}
