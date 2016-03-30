<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015-8-10
 * Time: 16:17
 */
namespace itm\domain;

use Yii;
use app\models\ZSoapClient;
use yii\base\Exception;

class RegAuthGreenChannel {
    protected $webservice_id = '';
    protected $pw = '';

    public function __construct($webservice_id = null,$pw = null) {
        $this->webservice_id = null == $webservice_id ? 'huyi1' : $webservice_id;
        $this->pw = null == $pw ? '7c4a8d09ca3762af61e59520943dc26494f8941b' : $pw;
    }

    /**
     * 审核通过
     * @param $array
     * @return array
     */
    public function RegistrantChangeAuditPass($array){
        $webserviceurl = Yii::$app->params ['reg_auth_green_webservice']['url'];
        try{
            try{
                $xml='<wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" ><wsse:UsernameToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd"><wsse:Username>'.$this->webservice_id.'</wsse:Username><wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">'.$this->pw.'</wsse:Password></wsse:UsernameToken></wsse:Security>';
                $header = new \SoapHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 'CallbackHandler', new \SoapVar($xml, XSD_ANYXML,null,null,null,'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd'), true);
                $opt = array('trace'=>3,'encoding'=>'utf-8', 'exceptions' => 1,'uri'=> "http://service.nightwish.gtld.knet.cn/", 'soap_version' => SOAP_1_1);
                $client = @new ZSoapClient($webserviceurl,$opt);
                $client->__setSoapHeaders(array($header));
                $result = $client->registrationAuditPass($array);
                $response = isset($result->return)?$result->return:false;
                if($response){
                    return array('code'=>1,'message'=>'操作成功');
                }else{
                    return array('code'=>0,'message'=>'操作失败');
                }
            }catch (\SoapFault $e) {
                Yii::error($e->faultstring, 'webservice');
                return array('code'=>0,'message'=>$e->faultstring);
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), 'webservice');
            return array('code'=>0,'message'=>'操作失败');
        }
    }

    /**
     * 审核拒绝
     * @param $array
     * @return array
     */
    public function RegistrantChangeAuditReject($array){
        $webserviceurl = Yii::$app->params ['reg_auth_green_webservice']['url'];
        try{
            try{
                $xml='<wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" ><wsse:UsernameToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd"><wsse:Username>'.$this->webservice_id.'</wsse:Username><wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">'.$this->pw.'</wsse:Password></wsse:UsernameToken></wsse:Security>';
                $header = new \SoapHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 'CallbackHandler', new \SoapVar($xml, XSD_ANYXML,null,null,null,'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd'), true);
                $opt = array('trace'=>3,'encoding'=>'utf-8', 'exceptions' => 1,'uri'=> "http://service.nightwish.gtld.knet.cn/", 'soap_version' => SOAP_1_1);
                $client = @new ZSoapClient($webserviceurl,$opt);
                $client->__setSoapHeaders(array($header));
                $result = $client->registrationAuditReject($array);
                $response = isset($result->return)?$result->return:false;
                if($response){
                    return array('code'=>1,'message'=>'操作成功');
                }else{
                    return array('code'=>0,'message'=>'操作失败');
                }
            }catch (\SoapFault $e) {
                Yii::error($e->faultstring, 'webservice');
                return array('code'=>0,'message'=>$e->faultstring);
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage(), 'webservice');
            return array('code'=>0,'message'=>'操作失败');
        }
    }

} 