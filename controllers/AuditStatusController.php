<?php
    /******************************
     * @author duan
     * create time 2015-08-13 16:00:00
     * YII框架API接口端的server (基于Webservice的接口服务器端)
     * 注意php.ini开启soap扩展:extension=php_soap.dll
     *******************************
     */
    namespace app\controllers;
    use yii;
    use yii\web\Controller;
    use app\extensions\Security;
    use app\models\Registrar;
    use app\models\HelpWebservice;
    use app\models\AuditData;
    class AuditStatusController extends Controller{


        public $registrar_id;

        public function actions()
        {
            return [
                'auditstatus' => [
                    'class' => 'conquer\services\WebServiceAction',
                    'classMap' => [
                        'GetDataAuditStateByContactIdAndTld'=>'app\models\GetDataAuditStateByContactIdAndTld',
                        'GetNamingAuditStateByDomainName'=>'app\models\GetNamingAuditStateByDomainName'
                    ],
                ],
            ];
        }


        public function Security($UsernameToken){

            $Username = $UsernameToken->UsernameToken->Username;
            $Password = $UsernameToken->UsernameToken->Password;
            Yii::error($Password, 'login_error');

            $info = Registrar::findOne(['webservice_id'=>$Username]);
            if(null==$info){
                $message = HelpWebservice::get_return_xml_message(['message'=>'用户不存在']);
                throw new \SoapFault('Server', $message);
            }

            if($info->webservice_password!==$Password){
                $message = HelpWebservice::get_return_xml_message(['message'=>'用户密码错误']);
                throw new \SoapFault('Server', $message);
            }
            Yii::error($info->id,'webservice');
            $this->registrar_id = $info->id;
            return true;
        }

        /**
         * @param app\models\GetDataAuditStateByContactIdAndTld
         * @return string
         * @soap
         */
        public function getDataAuditStateByContactIdAndTld($array)
        {
            Yii::error(json_decode(json_encode($array),true),'webservice');
            $array = json_decode(json_encode($array),true);
            Yii::error($this->registrar_id.'============','webservice');
            $contactId = isset($array['contactId'])?$array['contactId']:'';

            $tld = isset($array['tld'])?$array['tld']:'';

            if(empty($contactId)){
                $message = HelpWebservice::get_return_xml_message(['message'=>'联系人ID不能为空']);
                throw new \SoapFault('Server', $message);
            }
            if(empty($tld)){
                $message = HelpWebservice::get_return_xml_message(['message'=>'缺少tld参数']);
                throw new \SoapFault('Server', $message);
            }
            //查询资料表，联系人信息是否存在
            $contactids = AuditData::findAll(['contact_id'=>$contactId,'registrar_id'=>$this->registrar_id]);

            //联系人未注册过域名，或尚未提交资料
            if(empty($contactids)){
                $return = ['contactId'=>$contactId,'status'=>'未查询到审核信息','message'=>''];
                return $return;
            }
            $message = '';
            foreach($contactids as $info){
                if($info['audit_category']=='复审'){
                    $status = '审核通过';
                }else
                if($info['audit_result']=='审核拒绝'){
                    $status = $info['audit_result'];
                    $message = $info['display_reason'];
                }else{
                    $status = $info['audit_result'];
                }
                $return[] = ['contactId'=>$contactId,'domainName'=>$info->domain,'status'=>$status,'message'=>$message];
            }
            //$message = HelpWebservice::get_return_xml_message(['return'=>trim($str,',')]);
            return json_encode($return,JSON_UNESCAPED_UNICODE);
        }

        /**
         * @param app\models\GetNamingAuditStateByDomainName
         * @return string
         * @soap
         */
        public function GetNamingAuditStateByDomainName($array){

            Yii::error(json_decode(json_encode($array),true),'webservice');
            $array = json_decode(json_encode($array),true);

            $domainName = isset($array['domainName'])?$array['domainName']:'';

            if(empty($domainName)){
                $message = HelpWebservice::get_return_xml_message(['message'=>'域名不能为空']);
                throw new \SoapFault('Server', $message);
            }

            //查询资料表，域名信息是否存在
            $info= AuditData::find()->where("domain='{$domainName}' and registrar_id='{$this->registrar_id}'")->orderBy('created desc')->One();

            //域名未注册或尚未提交资料
            if(empty($info)){
                $return = ['domainName'=>$domainName,'status'=>'未查询到审核信息','message'=>''];
                return $return;
            }

            if($info['audit_category']=='初审'){
                $status = $info['audit_result']=='审核通过'?'审核中':($info['audit_result']=='已删除'?'已删除':'未查询到审核信息');
                $return = ['domainName'=>$domainName,'status'=>$status];
            }
            if($info['audit_result']=='审核拒绝'){
                $return = ['domainName'=>$domainName,'status'=>$status,'message'=>$info['display_reason']];
            }else{
                $status = $info['audit_result'];
                $return = ['domainName'=>$domainName,'status'=>$status,'message'=>''];
            }

            //$message = HelpWebservice::get_return_xml_message(['return'=>trim($str,',')]);
            return json_encode($return,JSON_UNESCAPED_UNICODE);
        }
    }

