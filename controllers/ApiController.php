<?php
    /**
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2015-9-22
     * Time: 15:13
     */

    namespace app\controllers;


    use app\models\AuditData;
    use yii\db\Expression;
    use yii\web\Controller;

    class ApiController extends Controller
    {
        public function acitonUpdateContact(){
            $contact_id = trim(Yii::$app->request->post('contact_id',''));
            if(empty($contact_id)){
                $this->decodemsg(['code'=>'1100','msg'=>'联系人Id不能为空']);
            }
            $email = Yii::$app->request->post('domain_agent_email','');
            $phone = Yii::$app->request->post('domain_agent_mobile','');
            $telephone = Yii::$app->request->post('domain_agent_tel','');
            $auditdatas = AuditData::find()->where("contact_id='{$contact_id}'")->all();
            if(null!==$auditdatas){
                foreach($auditdatas as $auditdata){
                    $auditdata->domain_agent_email = empty($email)?$auditdata->domain_agent_email:$email;
                    $auditdata->domain_agent_mobile = empty($email)?$auditdata->domain_agent_mobile:$phone;
                    $auditdata->domain_agent_tel = empty($email)?$auditdata->domain_agent_tel:$telephone;
                    $auditdata->modified = new Expression('NOW()');
                    $auditdata->contact_update_flag = '是';
                    $auditdata->contact_update = new Expression('NOW()');
                    $auditdata->update_count = $auditdata->update_count+1;
                    if($auditdata->save()){
                        $this->decodemsg(['code'=>'1000','msg'=>'更新成功']);
                    }else{
                        $this->decodemsg(['code'=>'1200','msg'=>'更新失败']);
                    }
                }

            }
        }

        /**
         * 格式化返回信息
         * @param $code
         * @param $message
         */
        private function decodemsg($data){
            $returndata = json_encode($data,JSON_UNESCAPED_UNICODE);
            //将返回信息记录在日志
            Yii::info('----我是分割线----返回值：'.$returndata,'api');
            echo $returndata;
            Yii::app()->end();
        }
    }