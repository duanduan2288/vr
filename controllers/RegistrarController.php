<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-11-20
 * Time: 下午1:36
 */

namespace app\controllers;
use Yii;
use yii\web\Controller;
use yii\base\Exception;
use yii\db\Expression;
use app\models\Service;
use app\models\Registrar;
use app\models\ServiceOperationLog;

class RegistrarController extends Controller{
    /**
     *完善公司信息
     */
    public function actionCreateCompany(){
        $uid = Yii::$app->user->id;
        if($uid>0){
            $model = new Registrar();
            return $this->render('createcompany',['model'=>$model]);
        }else{
            return $this->redirect('/site/login');
        }

    }

    /**
     * 修改公司信息
     */
    public function actionEditcompany(){
        $uid = Yii::$app->user->id;
        if(!$uid){
            $this->redirect('/site/login');
        }
        $id = Yii::$app->request->get('id','');
        $registrar = Registrar::findOne($id);
        if($registrar){
            return $this->render('createcompany',['model'=>$registrar]);
        }else{
            $this->error('公司不存在');
        }
    }
    /***
     * 保存公司信息
     */
    public function actionSavecompany(){

        $uid = Yii::$app->user->id;
        if(!$uid){
            $this->error('请先登录');
        }
        $epp1_password = $webservice1_password = '';

        $code = Yii::$app->request->post('code','');
        $company_name_zh_cn = Yii::$app->request->post('company_name_zh_cn','');

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $id = Yii::$app->request->post('id','');
            if(empty($company_name_zh_cn)){
                throw new Exception('单位名称不能为空');
            }
            if(!empty($id))
            {
                $model = $this->loadModel($id);
                $company = Registrar::find()->where('company_name_zh_cn = "'.$company_name_zh_cn.'" AND id !='.$id)->one();
                $successmessage = '修改成功';
            }
            if(empty($model))
            {
                $model = new Registrar();
                $model->guid = Service::create_guid();
                $model->webservice_id = 'ws'.$code;
                $model->webservice_password = sha1(Service::random_hash(8));
                $model->created = new Expression('NOW()');
                $successmessage = '添加成功';
                $company =  Registrar::find()->where('company_name_zh_cn = "'.$company_name_zh_cn.'"')->one();
                $before = '';
            }else{
                $webservice1_password = $model->webservice_password;
                $webservice_id = trim(Yii::$app->request->post('webservice_id',''));
                $webservice_password = trim(Yii::$app->request->post('webservice_password',''));
                $webservice_password = !empty($webservice_password)?sha1($webservice_password):$webservice1_password;
                $before = json_encode($model->attributes);
                $model->webservice_id = !empty($webservice_id) ? $webservice_id : $model->webservice_id;
                $model->webservice_password = $webservice_password;
            }

            if($company){
                throw new Exception('注册商已存在');
            }
            $company_url = Yii::$app->request->post('company_url','');
            if(empty($company_url)){
                throw new Exception('单位网址不能为空');
            }
            $abbreviation = Yii::$app->request->post('abbreviation','');
            if(empty($abbreviation)){
                throw new Exception('单位简称不能为空');
            }
            $model->code = strip_tags($code);
            $model->short_code = strip_tags($code);
            $model->epp_id = strip_tags(Yii::$app->request->post('epp_id',''));

            $model->company_leader = strip_tags(Yii::$app->request->post('company_leader',''));
            $model->company_contact = strip_tags(Yii::$app->request->post('company_contact',''));
            $model->company_leader_phone = strip_tags(Yii::$app->request->post('company_leader_phone',''));
            $model->company_contact_phone = strip_tags(Yii::$app->request->post('company_contact_phone',''));
            $model->abbreviation = strip_tags($abbreviation);
            $model->company_name_zh_cn = strip_tags($company_name_zh_cn);
            $model->company_name_en_us = strip_tags(Yii::$app->request->post('company_name_en_us',''));
            $model->industry_type = strip_tags(Yii::$app->request->post('industry_type',''));
            $model->city_zh_cn = strip_tags(Yii::$app->request->post('city_zh_cn',''));
            $model->province_zh_cn = strip_tags(Yii::$app->request->post('province_zh_cn',''));
            $model->country_code = strip_tags(Yii::$app->request->post('country_code',''));
            $model->country_type = strip_tags(Yii::$app->request->post('country_type',''));
            $model->company_url = strip_tags(Yii::$app->request->post('company_url',''));
            $model->company_address = strip_tags(Yii::$app->request->post('company_address',''));
            $model->other = strip_tags(Yii::$app->request->post('other',''));
            $model->modified = new Expression('NOW()');
            $model->status = '正常';
            if(!$model->save()){
                throw new Exception(json_encode($model->errors,JSON_UNESCAPED_UNICODE));
            }
            ServiceOperationLog::create_operation_log(json_encode($model->attributes),
                $before,'添加/修改注册商信息','/registrar/save',$uid);

            $transaction->commit();
            $this->success($successmessage);
        }catch(Exception $e){
            $transaction->rollBack();
            $this->error($e->getMessage());
        }
    }

    /**
     * 注册验证shart_code是否存在  (暂时无用)
     */
    public function actionCheckshortcode()
    {
        $short_code = yii::app()->request->post('short_code', '');
        $company_id = yii::app()->request->post('company_id', '');
        $result = true;
        $str = $company_id ? "short_code = '{$short_code}' and id != {$company_id}" : "short_code = '{$short_code}'";
        if (null==Registrar::find()->where($str)->one()) {
            $result = false;
        }
        echo json_encode($result);
        Yii::$app->end();
    }

    public function loadModel($id){
        $registrar = Registrar::findOne($id);
        if($registrar===null)
            throw new Exception('公司不存在');
        return $registrar;
    }

} 