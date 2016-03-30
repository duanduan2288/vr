<?php
    namespace itm\domain;
    use Yii;
    use app\models\ZSoapClient;
    use yii\base\Exception;
    /**
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2014-12-26
     * Time: 14:01
     */
    class WebUpload{

        protected $epp_id = '';
        protected $pw = '';

        public function __construct($epp_id = null,$pw = null) {
            $this->epp_id = null == $epp_id ? 'huyi1' : $epp_id;
            $this->pw = null == $pw ? '7c4a8d09ca3762af61e59520943dc26494f8941b' : $pw;
        }

        public function UploadAuditData($array){
            $webserviceurl = Yii::$app->params ['webservice']['url'];
            try{
                try{
                    $xml='<wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" ><wsse:UsernameToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd"><wsse:Username>'.$this->epp_id.'</wsse:Username><wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">'.$this->pw.'</wsse:Password></wsse:UsernameToken></wsse:Security>';
                    $header = new \SoapHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 'CallbackHandler', new \SoapVar($xml, XSD_ANYXML,null,null,null,'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd'), true);
                    $opt = array('trace'=>3,'encoding'=>'utf-8', 'exceptions' => 1,'uri'=> "http://service.nightwish.gtld.knet.cn/", 'soap_version' => SOAP_1_1);
                    $client = @new ZSoapClient($webserviceurl,$opt);
                    $client->__setSoapHeaders(array($header));
                    $result = $client->uploadAuditData($array);
                    return array('code'=>1,'message'=>'上传成功');
                }catch (\SoapFault $e) {
                    Yii::error($e->faultstring, 'webservice');
                    return array('code'=>0,'message'=>$e->faultstring);
                }
            } catch (Exception $e) {
                Yii::error($e->getMessage(), 'webservice');
                return array('code'=>0,'message'=>'上传失败');
            }
        }

        /**
         * 查询审核状态
         * @param $array
         * @return array
         */
        public function DomainAuditStateService($array){
            $webserviceurl = Yii::$app->params ['AuditStatewebservice']['url'];
            try{
                try{
                    $xml='<wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" ><wsse:UsernameToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd"><wsse:Username>'.$this->epp_id.'</wsse:Username><wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">'.$this->pw.'</wsse:Password></wsse:UsernameToken></wsse:Security>';
                    $header = new \SoapHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 'CallbackHandler', new \SoapVar($xml, XSD_ANYXML,null,null,null,'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd'), true);
                    $opt = array('trace'=>3,'encoding'=>'utf-8', 'exceptions' => 1,'uri'=> "http://service.nightwish.gtld.knet.cn/", 'soap_version' => SOAP_1_1);
                    $client = @new ZSoapClient($webserviceurl,$opt);
                    $client->__setSoapHeaders(array($header));
                    $result = $client->getDataAuditStateByContactIdAndTld($array);
                    return ['code'=>1,'message'=>isset($result->return)?$result->return:''];
                }catch (\SoapFault $e) {
                    Yii::error($e->faultstring, 'webservice');
                    return array('code'=>0,'message'=>$e->faultstring,'a'=>$array);
                }
            } catch (Exception $e) {
                Yii::error($e->getMessage(), 'webservice');
                return array('code'=>0,'message'=>'查询失败');
            }
        }

        /**
         * 命名审核状态
         * @param $array
         * @return array
         */
        public function GetNamingAuditStateByDomainName($array){
            $webserviceurl = Yii::$app->params ['AuditStatewebservice']['url'];
            try{
                try{
                    $xml='<wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" ><wsse:UsernameToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd"><wsse:Username>'.$this->epp_id.'</wsse:Username><wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">'.$this->pw.'</wsse:Password></wsse:UsernameToken></wsse:Security>';
                    $header = new \SoapHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 'CallbackHandler', new \SoapVar($xml, XSD_ANYXML,null,null,null,'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd'), true);
                    $opt = array('trace'=>3,'encoding'=>'utf-8', 'exceptions' => 1,'uri'=> "http://service.nightwish.gtld.knet.cn/", 'soap_version' => SOAP_1_1);
                    try{
                        $client = @new ZSoapClient($webserviceurl,$opt);
                    }catch (\SoapFault $e) {
                        Yii::error($e->faultstring, 'webservice');
                        return array('code'=>0,'message'=>$e->faultstring,'a'=>$array);
                    }

                    $client->__setSoapHeaders(array($header));
                    $result = $client->getNamingAuditStateByDomainName($array);
                    return ['code'=>1,'message'=>isset($result->return)?$result->return:''];
                }catch (\SoapFault $e) {
                    Yii::error($e->faultstring, 'webservice');
                    return array('code'=>0,'message'=>$e->faultstring,'a'=>$array);
                }
            } catch (Exception $e) {
                Yii::error($e->getMessage(), 'webservice');
                return array('code'=>0,'message'=>'查询失败');
            }
        }
    }