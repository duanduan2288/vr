<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015-9-2
 * Time: 13:47
 */

namespace app\controllers;
use yii;
use yii\web\Controller;
use yii\base\Exception;
use app\models\Issue;
use conquer\services\WsdlGenerator;
use app\models\HelpWebservice;
use yii\validators\DateValidator;

class TestController extends Controller{

    public function actionCopy(){
        $output =shell_exec('cp -n /udiska/upload/test.7z /udiska/upload');
        print_r($output.'<br/>');
    }
    function actionIndex(){

        //$webservice = new WsdlGenerator();
        //$webservice->bindingStyle = WsdlGenerator::STYLE_RPC;
        //$aa = $webservice->generateWsdl('app\controllers\AuditServiceController','http://admin.audit.com/audit-service/soap');
        //$fb = fopen('audit.wsdl','w');
        //fwrite($fb,$aa);
        //fclose($fb);
        //var_dump($aa);die;

        $soap_url = 'http://admin.audit.com/audit.wsdl';
        try {
            try{
                $arr = [
                    'registrarId' => '12345',
                    'domain' => '格润丝.商标',
                    'contactId' => '1481s6qf5e8y',
					'registorType'=>2,
                    'contactCode'=>'1',
                    'contactValue'=>'412725198811193425',
					'org'=>1234561212,
					'orgCode'=>1234561212,
					'businessLicense'=>1234561212122,
                    'registeredYears'=>10,
                    'parseUrl'=>'http://www.huyi.com',
                    'trademarkRegNo'=>'156257383927',
                    'tmClassType'=>'第一类,第二类',
                    'tmIssuingCountry'=>'CN',
                    'tmProofExpiresDate'=>'2020-12-03',
                    'otherParam'=>'',
                ];

                $fp12 = fopen(Yii::$app->basePath.'/20150210161931893.jpg', 'rb',0);
                $file12 = fread($fp12, filesize(Yii::$app->basePath.'/20150210161931893.jpg')); //二进制数据
                fclose($fp12);

                $file1 = ['dataHandler'=>base64_encode($file12),'fileName'=>'id.jpg','type'=>1,'fileSuffix'=>'jpg'];

                $file2 = ['dataHandler'=>base64_encode($file12),'fileName'=>'org.jpg','type'=>2,'fileSuffix'=>'jpg'];
                $file3 = ['dataHandler'=>base64_encode($file12),'fileName'=>'business.jpg','type'=>3,'fileSuffix'=>'jpg'];
                $files = [$file1,$file2,$file3];
                $arr['files'] = $files;

                $xml = '<wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" ><wsse:UsernameToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd"><wsse:Username>huyi1</wsse:Username><wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">' . sha1('123456') . '</wsse:Password></wsse:UsernameToken></wsse:Security>';

                $header = new \SoapHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 'CallbackHandler', new \SoapVar($xml, XSD_ANYXML), true);
                $opt = array('trace'=>1,'encoding'=>'utf-8', 'exceptions' => 1,'uri'=> "http://service.nightwish.gtld.knet.cn/", 'soap_version' => SOAP_1_2);

                $client = new \SoapClient($soap_url,$opt);
                $client->__setSoapHeaders(array($header));
                $res = $client->UploadAuditData($arr);
                var_dump($res);die;
            }catch (\SoapFault $e) {
                var_dump($e->faultstring);die;
            }
        }catch (Exception $e){
            var_dump($e->getMessage());die;
        }
    }

    public function actionStatus(){
        $soap_url = 'http://admin.audit.com/aa.wsdl';
        try {
            try{
                $arr = [
                    'domainName' => 'audit1.商标',
                    //'domain' => 'duan030.商标',
                    //'contactId' => '1481s6qf5e8y',
                ];

                $xml = '<wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" ><wsse:UsernameToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd"><wsse:Username>huyi1</wsse:Username><wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">' . sha1('123456') . '</wsse:Password></wsse:UsernameToken></wsse:Security>';

                $header = new \SoapHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 'CallbackHandler', new \SoapVar($xml, XSD_ANYXML), true);
                $opt = array('trace'=>1,'encoding'=>'utf-8', 'exceptions' => 1,'uri'=> "http://service.nightwish.gtld.knet.cn/", 'soap_version' => SOAP_1_2);

                $client = new \SoapClient($soap_url,$opt);
                $client->__setSoapHeaders(array($header));
                $res = $client->GetNamingAuditStateByDomainName($arr);
                var_dump($res);die;
            }catch (\SoapFault $e) {
                var_dump($e->faultstring);die;
            }
        }catch (Exception $e){
            var_dump($e->getMessage());die;
        }
    }

} 