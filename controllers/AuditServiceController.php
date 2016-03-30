<?php
    namespace app\controllers;
    use app\models\AuditData;
    use app\models\Dictionary;
    use app\models\Helper;
    use Yii;
    use yii\db\Expression;
    use yii\web\Controller;
    use app\models\Registrar;
    use app\models\HelpWebservice;
    use itm\domain\WebUpload;
    use yii\base\Exception;
    use app\models\Issue;
    use app\models\AuditIssue;
    use app\models\Service;
    use yii\validators\EmailValidator;
    use yii\validators\UrlValidator;
    use yii\validators\DateValidator;
    use app\models\EppOperationLog;

    /******************************
     * @author duan
     * create time 2015-08-13 16:00:00
     * YII框架API接口端的server (基于Webservice的接口服务器端)
     * 注意php.ini开启soap扩展:extension=php_soap.dll
     *******************************
     */
    class AuditServiceController extends Controller{

        public function actions()
        {
            return [
                'soap' => [
                    'class' => 'conquer\services\WebServiceAction',
                    'classMap' => ['MyClass'=>'app\models\UploadAuditData'],
                    'serviceOptions'=>[
                        'generatorConfig'=>'app\models\MyWsdlGenerator'
                    ]
                ],
            ];
        }

        public function Security($UsernameToken){

            $Password = $UsernameToken->UsernameToken->Password;
            $Username = $UsernameToken->UsernameToken->Username;
            $info = Registrar::findOne(['webservice_id'=>$Username]);
            if(null==$info){
                $message = HelpWebservice::get_return_xml_message(['message'=>'用户不存在']);
                throw new \SoapFault('Server', $message);
            }
            if($info->webservice_password!==$Password){
                $message = HelpWebservice::get_return_xml_message(['message'=>'用户密码错误']);
                throw new \SoapFault('Server', $message);
            }
            return true;
        }

        /**
         *
         * @param app\models\UploadAuditData
         * @return bool
         * @soap
         */
        public function UploadAuditData($array)
        {
            Yii::error($array,'webservice');
            $array = json_decode(json_encode($array),true);
            Yii::error($array,'webservice');
            $contactcodes = [1,2,3];
            //注册商eppid
            $registrarId = isset($array['registrarId'])?$array['registrarId']:'';
            if(empty($registrarId)){
                $message = HelpWebservice::get_return_xml_message(['message'=>'注册商ID不能为空']);
                throw new \SoapFault('Server', $message);
            }
            //域名
            $domain = strtolower(isset($array['domain'])?$array['domain']:'');
            if(empty($domain)){
                $message = HelpWebservice::get_return_xml_message(['message'=>'缺少域名参数']);
                throw new \SoapFault('Server', $message);
            }
            //联系人id
            $contactId = strtolower(isset($array['contactId'])?$array['contactId']:'');
            if(empty($contactId)){
                $message = HelpWebservice::get_return_xml_message(['message'=>'缺少联系人ID参数']);
                throw new \SoapFault('Server', $message);
            }
            //组织类型
            $registorType = intval(isset($array['registorType'])?$array['registorType']:'');

            if(empty($registorType)){
                $message = HelpWebservice::get_return_xml_message(['message'=>'请填写组织类型']);
                throw new \SoapFault('Server', $message);
            }

            if($registorType!=1 && $registorType!=2){
                $message = HelpWebservice::get_return_xml_message(['message'=>'组织类型错误']);
                throw new \SoapFault('Server', $message);
            }

            //联系人证件类型
            $contactCode = isset($array['contactCode'])?$array['contactCode']:'';
            if(empty($contactCode)){
                $message = HelpWebservice::get_return_xml_message(['message'=>'联系人证件类型不能为空']);
                throw new \SoapFault('Server', $message);
            }

            if(!in_array(intval($contactCode),$contactcodes)){
                $message = HelpWebservice::get_return_xml_message(['message'=>'联系人证件类型错误']);
                throw new \SoapFault('Server', $message);
            }

            //联系人证件号码
            $contactValue = isset($array['contactValue'])?$array['contactValue']:'';
            if(empty($contactValue)){
                $message = HelpWebservice::get_return_xml_message(['message'=>'请填写联系人证件号码']);
                throw new \SoapFault('Server', $message);
            }
            //检查联系人证件号码是否符合格式
            if(!HelpWebservice::check_id_number($contactCode,$contactValue)){
                $message = HelpWebservice::get_return_xml_message(['message'=>'请填写联系人证件号码格式错误']);
                throw new \SoapFault('Server', $message);
            }
            //组织机构代码证
            $org = isset($array['org'])?$array['org']:'';
            if(empty($org) && $registorType==2){
                $message = HelpWebservice::get_return_xml_message(['message'=>'请填写组织机构代码证号码']);
                throw new \SoapFault('Server', $message);
            }

            //组织机构代码证编号
            $orgCode = isset($array['orgCode'])?$array['orgCode']:'';

            if(empty($orgCode) && $registorType==2){
                $message = HelpWebservice::get_return_xml_message(['message'=>'请填写组织机构代码证编号']);
                throw new \SoapFault('Server', $message);
            }
            //检查编号格式是否正确
            if(!is_int($orgCode) && (9!=strlen($orgCode) && strlen($orgCode)!=10)){
                $message = HelpWebservice::get_return_xml_message(['message'=>'请填写正确的组织机构代码证编号']);
                throw new \SoapFault('Server', $message);
            }
            //营业执照编号
            $businessLicense = isset($array['businessLicense'])?$array['businessLicense']:'';
            if(!empty($businessLicense)){
               if(!is_int($businessLicense) && (strlen($businessLicense)!=13 && strlen($businessLicense)!=15)){
                   $message = HelpWebservice::get_return_xml_message(['message'=>'请填写正确的营业执照编号']);
                   throw new \SoapFault('Server', $message);
               }
            }
            //域名解析网址
            $parseUrl = isset($array['parseUrl'])?$array['parseUrl']:'';
            if(!empty($parseUrl)){
                $urlValidator = new UrlValidator();
                if(!$urlValidator->validate($parseUrl)){
                    $message = HelpWebservice::get_return_xml_message(['message'=>'域名解析网址格式错误']);
                    throw new \SoapFault('Server', $message);
                }
            }

            //商标注册号
            $trademarkRegNo = isset($array['trademarkRegNo'])?$array['trademarkRegNo']:'';

            //商标类别
            $tmClassType = isset($array['tmClassType'])?$array['tmClassType']:'';

            //商标颁发国
            $tmIssuingCountry = strtoupper(isset($array['tmIssuingCountry'])?$array['tmIssuingCountry']:'');
            if(!empty($tmIssuingCountry)) {
                if(!isset(Dictionary::$countries[$tmIssuingCountry])){
                    $message = HelpWebservice::get_return_xml_message(['message' => '商标颁发国格式错误']);
                    throw new \SoapFault('Server', $message);
                }
            }
            //商标证书到期日期
            $tmProofExpiresDate = isset($array['tmProofExpiresDate'])?$array['tmProofExpiresDate']:'';

            if(!empty($tmProofExpiresDate)) {
                $dateValidator = new DateValidator();
                $dateValidator->format = 'yyyy-mm-dd';
                if (!empty($tmProofExpiresDate) && !$dateValidator->validate($tmProofExpiresDate)) {
                    $message = HelpWebservice::get_return_xml_message(['message' => '商标证书到期日期格式错误']);
                    throw new \SoapFault('Server', $message);
                }
                if (date('Y-m-d') > $tmProofExpiresDate) {
                    $message = HelpWebservice::get_return_xml_message(['message' => '您的商标证书已到期']);
                    throw new \SoapFault('Server', $message);
                }
            }
            //域名经办人
            $domainAgent = isset($array['domainAgent'])?$array['domainAgent']:'';

            //域名经办人电话
            $domainAgentTel = isset($array['domainAgentTel'])?$array['domainAgentTel']:'';
            //if(!empty($domainAgentTel)){
            //    if(!preg_match('/^(([0\\+]\\d{2,3}.)?(0\\d{2,3})-)?(\\d{7,8})(-(\\d{3,}))?$/',$domainAgentTel)){
            //        $message = HelpWebservice::get_return_xml_message(['message'=>'域名经办人电话格式错误']);
            //        throw new \SoapFault('Server', $message);
            //    }
            //}
            //域名经办人手机
            $domainAgentMobile = isset($array['domainAgentMobile'])?$array['domainAgentMobile']:'';
            if(!empty($domainAgentMobile)){
                if(!preg_match('/^1[0-9]{10}$/',$domainAgentMobile)){
                    $message = HelpWebservice::get_return_xml_message(['message'=>'域名经办人手机格式错误']);
                    throw new \SoapFault('Server', $message);
                }
            }
            //域名经办人邮箱
            $domainAgentEmail = isset($array['domainAgentEmail'])?$array['domainAgentEmail']:'';
            if(!empty($domainAgentEmail) && is_string($domainAgentEmail)){
                $emailValidator = new EmailValidator();
                if(!$emailValidator->validate($domainAgentEmail)){
                    $message = HelpWebservice::get_return_xml_message(['message'=>'域名经办人邮箱格式错误']);
                    throw new \SoapFault('Server', $message);
                }
            }
            //域名经办人地址
            $domainAgentAddress = isset($array['domainAgentAddress'])?$array['domainAgentAddress']:'';
            //其他参数
            $otherParam = isset($array['otherParam'])?$array['otherParam']:'';

            //附件
            $files = isset($array['files'])?$array['files']:[];
            if(empty($files)){
                $message = HelpWebservice::get_return_xml_message(['message'=>'文件不能为空']);
                throw new \SoapFault('Server', $message);
            }
            //检查注册商id是否存在
            $registrar = Registrar::findOne(['epp_id'=>$registrarId]);
            if(null===$registrar){
                $message = HelpWebservice::get_return_xml_message(['message'=>'注册商epp ID不存在']);
                throw new \SoapFault('Server', $message);
            }

            //将资料提交到北龙
            $returndata = $this->webservice_upload($array,$registrar);
            if(!empty($returndata) && $returndata['code']!=1){
                $reason = isset($returndata['message'])?$returndata['message']:'上传失败';
                $message = HelpWebservice::get_return_xml_message(['message'=>$reason]);
                throw new \SoapFault('Server', $message);
            }
            //$this->checkDomain($contactId,$registrar,$domain);

            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                //保存审核资料等信息
                $auditData = AuditData::find()
                    ->where("domain='{$domain}'")
                    ->andWhere("contact_id='{$contactId}'")
                    ->andWhere("audit_category='初审'")
                    ->andWhere("audit_result not in ('审核通过','已删除','审核拒绝')")
                    ->andWhere("registrar_epp_id='{$registrarId}'")
                    ->one();
                if(null==$auditData){
                    $auditData = new AuditData();
                    $auditData->guid = Service::create_guid();
                    $auditData->domain = $domain;
                    $auditData->contact_id = $contactId;
                    $auditData->registrar_epp_id = $registrarId;
                    $auditData->audit_category = '初审';
                    $auditData->audit_result = '待审核';
                    $auditData->created = new Expression('NOW()');
                    $auditData->operator_id= 0;
                    $auditData->registrar_id = $registrar->id;
                }
                $auditData->update_flag = '是';
                $auditData->registrant_type = $registorType==2?'企业':'个人';
                $auditData->contact_code = $contactCode;
                $auditData->contact_value = $contactValue;
                $auditData->org = $org;
                $auditData->org_code = $orgCode;
                $auditData->other_param = json_encode($otherParam);
                $auditData->business_license = $businessLicense;
                $auditData->organization_type = !empty($orgCode)?'组织机构代码证':'营业执照';
                $auditData->tm_expires_date = $tmProofExpiresDate;
                $auditData->parse_url = $parseUrl;
                $auditData->trademark_reg_no = $trademarkRegNo;
                $auditData->tm_class_type = $tmClassType;
                $auditData->tm_issuing_country = $tmIssuingCountry;
                $auditData->domain_agent = $domainAgent;
                $auditData->domain_agent_tel = $domainAgentTel;
                $auditData->domain_agent_mobile = $domainAgentMobile;
                $auditData->domain_agent_email = $domainAgentEmail;
                $auditData->domain_agent_address = (string)$domainAgentAddress;
                $auditData->feedback_status = '待回访';

                $filetype_arr = [1,2,3,4];
                $extarr = ['jpg','jpeg','bmp','gif','png'];

                foreach($files as $file){

                    $filename = $file['fileName'];
                    if(empty($filename)){
                        throw new \SoapFault('Server', '文件名称不能为空');
                    }

                    $data = $file['dataHandler'];
                    if(empty($data)){
                        throw new \SoapFault('Server', '文件不能为空');
                    }

                    $type = $file['type'];
                    if(!in_array($type,$filetype_arr)){
                        $message = HelpWebservice::get_return_xml_message(['message'=>$filename.'文件类型错误']);
                        throw new \SoapFault('Server', $message);
                    }
                    //文件扩展名
                    $ext = $file['fileSuffix'];
                    if(!in_array($ext,$extarr)){
                        $message = HelpWebservice::get_return_xml_message(['message'=>$filename.'文件类型不支持，请上传jpg,jpeg,bmp,gif,png等类型的文件']);
                        throw new \SoapFault('Server', $message);
                    }

                    $file_guid = Helper::save_base_to_image('-1',$filename,$data,$ext,$registrar->id);

                    switch($type){
                        case 1:
                            $auditData->id_file = $file_guid;
                            break;
                        case 2:
                            $auditData->org_file = $file_guid;
                            break;
                        case 3:
                            $auditData->business_file = $file_guid;
                            break;
                        case 4:
                            $others = [];
                            if(!empty($auditData->other_file)){
                                $others = json_decode($auditData->other_file,true);
                            }
                            array_push($others,$file_guid);
                            $auditData->other_file = json_encode($others);
                            break;
                    }
                }

                $auditData->modified = new Expression('NOW()');

                $issue = false;

                $guid = $auditData->issue_id?$auditData->issue_id:Service::create_guid();

                if($auditData->issue_id){
                    $issuemodel = new Issue();
                    $issue = $issuemodel->getIssue($auditData->issue_id);
                }
                $auditData->issue_id = $guid;

                if(!$auditData->save()){
                    throw new Exception(json_encode($auditData->errors,JSON_UNESCAPED_UNICODE));
                }
                Yii::error(json_encode($auditData->errors,JSON_UNESCAPED_UNICODE).'audit','webservice');

                if(false==$issue){
                    $issue = new AuditIssue();
                    $issue->guid = $guid;
                    $issue->domain = $domain;
                    $issue->name = 'AuditIssue';
                    $issue->audit_status = '待审核';
                    $issue->current_state = '已提交';
                    $issue->operator = 0;
                    $issue->assignee_company_table = 'registry_user';
                    $issue->assignee_id = 0;
                }
                $issue->userId = -1;
                $issue->attached_table = 'audit_data';
                $issue->attached_id = $auditData->id;
                $res = $issue->save();

                Yii::error(json_encode($issue->errors,JSON_UNESCAPED_UNICODE).'issue','webservice');
                if(!$res){
                    throw new Exception(json_encode($issue->errors,JSON_UNESCAPED_UNICODE));
                }
                $transaction->commit();

                $message = HelpWebservice::get_return_xml_message(['message'=>true]);
                return $message;
            } catch(Exception $e) {
                $transaction->rollBack();
                Yii::error($e->getMessage(),'webservice');
                throw new \SoapFault('Server', $e->getMessage());
            }
        }

        private function webservice_upload($array,$registrar){
            $requestarr = [
                'registrarId' => $array['registrarId'],
                'domain' => $array['domain'],
                'contactId' => $array['contactId'],
                'contactCode'=>$array['contactCode'],
                'contactValue'=>$array['contactValue'],
                'otherParam'=>$array['otherParam'],
                'registorType'=>$array['registorType'],
                'businessLicense'=>$array['businessLicense'],
                'org'=>$array['org'],
                'orgCode'=>$array['orgCode'],
                'files'=>$array['files']
            ];
            $webupload = new WebUpload($registrar['webservice_id'],$registrar['webservice_password']);
            $returndata = $webupload->UploadAuditData($requestarr);
            unset($requestarr['files']);
            EppOperationLog::insert(-1,'uploadAuditData','http://gtld.knet.cn/','9944',json_encode($requestarr,JSON_UNESCAPED_UNICODE),'8006',json_encode($returndata,JSON_UNESCAPED_UNICODE));
            return $returndata;
        }
        /**
         * 检查域名是否可以提交资料
         * @param $contactId
         * @param $registrar
         * @param $domain
         * @throws \SoapFault
         */
        private function checkDomain($contactId,$registrar,$domain)
        {
            //检查域名是否存在
            $epp = new EPP('-1',null,null,$registrar->epp_id,$registrar->epp_password);

            $info = $epp->infoEPPDomain($domain);

            if($info['info']!=='ok')
            {
                if($info['data']['code']=='2303')
                {
                    throw new \SoapFault('Server', '抱歉,您查询的域名不存在');
                }
                else
                {
                    throw new \SoapFault('Server', 'EPP ID错误');
                }

            }
            //检查域名是否属于此注册商
            if(empty($info['data']['password']))
            {
                throw new \SoapFault('Server', '域名不属于您');
            }
            //检查联系人id是否匹配
            if($contactId!=$info['data']['contactId'])
            {
                throw new \SoapFault('Server', '联系人和域名不匹配');
            }
            //检查域名的当前状态
           if(in_array('ok',$info['data']['statuses'])){
               throw new \SoapFault('Server', '域名已审核通过，不能再提交资料');
           }
        }


    }