<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014-12-19
 * Time: 16:13
 */
namespace itm\epp;
use Yii;
use yii\base\Exception;
use itm\epp\EPPRequest;
use itm\epp\EPPParser;
use app\models\EppOperationLog;
use app\models\Service;
use app\extensions\Ext_IdnaConvert;

class EPP {

    protected $hostname = "localhost";
    protected $port = '';
    protected $epp_id = '';
    protected $pw = '';
    protected $uid = '-1';

    public function __construct($uid = '-1',$hostname = null, $port= null,$epp_id = null,$pw = null) {
        $this->hostname = null == $hostname ? Yii::$app->params ['socket']['hostname'] : $port;
        $this->port = null == $port ? Yii::$app->params ['socket']['port'] : $port;
        $this->epp_id = null == $epp_id ? '' : $epp_id;
        $this->pw = null == $pw ? '' : $pw;
        $this->uid = null == $uid ? '' : $uid;
    }

    /**
     * 创建联系人
     * @param $cid联系人id
     * @param $company公司名
     * @param $fname名字
     * @param $lname姓氏
     * @param $addr地址
     * @param $state州或省
     * @param $city城市
     * @param $district区
     * @param $postCode邮编
     * @param $telphone电话
     * @param $telephoneExt分机号
     * @param string $fax传真号
     * @param string $faxExt传真分机号
     * @param $country国际代号
     * @param $email电邮
     * @param $password密码
     * @return array
     */
    public function createEPPContact($cid,$company,$fname,$lname,$addr,$state,$city,$district,$postCode,
                                     $telephone,$telphoneExt,$fax='',$faxExt='',$country,$email,$password){


        $array = [
            'huyiContact'=>[
                'cid'=>$cid,
                'company'=>$company,
                'fname'=>$fname,
                'lname'=>$lname
            ],
            'trID'=>[
                'clientId'=>1
            ]
        ];
        if(!empty($addr)){
            $address = [];
            foreach($addr as $add){
                $address[] = ['addres'=>$add];
            }
            $array['huyiContact']['addr'] = $address;
        }
        if(!empty($state)){
            $array['huyiContact']['state'] = $state;
        }
        if(!empty($city)){
            $array['huyiContact']['city'] = $city;
        }
        if(!empty($district)){
            $array['huyiContact']['district'] = $district;
        }
        $array['huyiContact']['postCode'] = $postCode;
        $array['huyiContact']['telephone'] = $telephone;
        if(!empty($telphoneExt)){
            $array['huyiContact']['telphoneExt'] = $telphoneExt;
        }
        $array['huyiContact']['country'] = $country;
        $array['huyiContact']['email'] = $email;
        $array['huyiContact']['password'] = $password;

        if(!empty($fax)){
            $array['huyiContact']['fax'] = $fax;
        }
        if(!empty($faxExt)){
            $array['huyiContact']['faxExt'] = $faxExt;
        }
        try{
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($array,'createEPPContact');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parsercreatecontact();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $data = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$data,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'checkEPPDomain',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($array),$code,$data);
        return $returndata;
    }

    /**
     * 检查联系人ID是否可以注册
     * @param $contactId联系人id
     * @return array
     */
    public function checkEPPContact($contactId){
        $array = [
            'checkContact'=>[
                'contactId'=>$contactId
            ],
            'trID'=>[
                'clientId'=>1
            ]
        ];
        try{
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($array,'checkEPPContact');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parsercheckcontact();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $data = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$data,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'checkEPPContact',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($array),$code,$data);
        return $returndata;
    }

    /**
     * 查询联系人详细信息
     * @param $contactId联系人id
     * @return array
     */
    public function infoEPPContact($contactId){

        $array = [
            'huyiInfoContact'=>['contactId'=>$contactId],
            'trID'=>[
                'clientId'=>1
            ]
        ];
        try{
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($array,'infoEPPContact');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parserinfoEPPContact();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $data = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$data,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'checkEPPContact',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($array),$code,$data);
        return $returndata;
    }


    /**
     * 删除联系人ID
     * @param $contactId
     * @return array
     */
    public function deleteEPPContact($contactId){
        $array = [
            'huyiDeleteContact'=>['contactId'=>$contactId],
            'trID'=>[
                'clientId'=>1
            ]
        ];
        try{
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($array,'deleteEPPContact');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parserCommonXml();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $message = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$message,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'deleteEPPContact',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($array),$code,$data);
        return $returndata;
    }

    /**
     * 更新联系人
     * @param $data要修改的信息
     * @return array
     */
    public function updateEPPContact($data){

        $newarray = [
            'huyiUpdateContact'=>$data,
            'trID'=>[
                'clientId'=>1
            ]
        ];
        try{
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($newarray,'updateEPPContact');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parserCommonXml();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $message = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$message,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'checkEPPDomain',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($newarray),$code,$data);
        return $returndata;
    }

    /**
     * 创建域名
     * @param $domain域名
     * @param $password域名密码
     * @param $contactId注册人Id
     * @param $adminId管理联系人Id
     * @param $teachId技术联系人Id
     * @param $renewId续费联系人Id
     * @param $period注册年限
     * @param array $nses DNS
     * @return bool
     */
    public function createEPPDomain($domain,$password,$contactId,$adminId,$teachId,$renewId,$period,$nses = array()){
        $nss = [];
        if(!empty($nses)){
            foreach($nses as $ns){
                $nss[] = [
                   'ns'=>['ns'=>$ns]
                ];
            }
        }
        $array = [
            'huyiDomain'=>
                [
                    'domain'=>$domain,
                    'password'=>$password,
                    'contactId'=>$contactId,
                    'adminId'=>$adminId,
                    'teachId'=>$teachId,
                    'renewId'=>$renewId,
                    'period'=>$period,
                    'nses'=>$nss
                ],
            'trID'=>[
                'clientId'=>1
            ]
        ];
        try{
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($array,'createEPPDomain');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parsercreateEPPDomain();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $message = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$message,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'createEPPDomain',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($array),$code,$data);
        return $returndata;
    }

    /**
     * 检查域名是否可以注册
     * @param $name域名
     * @return bool
     */
    public function checkEPPDomain($names){
        try{
            $domain = [];
            if(empty($names)){
                throw new Exception('参数错误','8002');
            }
            foreach((array)$names as $name){
                $domain[] = ['name'=>$name];
            }
            $array = [
                'huyiCheckDomain'=>[
                    'domain'=>$domain
                ],
                'trID'=>[
                    'clientId'=>1
                ]
            ];
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($array,'checkEPPDomain');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parsercheckdomain();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
               $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch (Exception $e){
            $code = $e->getCode();
            $message = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$message,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'checkEPPDomain',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($array),$code,$data);
        return $returndata;
    }

    /**
     * 获取域名详细信息
     * @param $domain
     * @return bool
     */
    public function infoEPPDomain($domain,$password=null){
        $array = [
            'huyiInfoDomain'=>['domain'=>$domain,'password'=>$password],
            'trID'=>[
                'clientId'=>Service::create_guid()
            ]
        ];
        try{
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($array,'infoEPPDomain');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parserinfoEPPDomain();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $message = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$message,'code'=>$code));
        }
        $uid = '-1';
        EppOperationLog::insert($uid,'infoEPPDomain',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($array),$code,$data);
        return $returndata;
    }

    /**
     * 域名续费
     * @param $domain域名
     * @param $expDate域名到期日期
     * @param $period续费年数
     * @return array
     */
    public function renewEPPDomain($domain,$expDate,$period){
        $array = [
            'huyiRenewDomain'=>[
                'domain'=>$domain,
                'expDate'=>$expDate,
                'period'=>$period
            ],
            'trID'=>[
                'clientId'=>1
            ]
        ];
        try{
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($array,'renewEPPDomain');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parserCommonXml();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $message = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$message,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'renewEPPDomain',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($array),$code,$data);
        return $returndata;
    }

    /**
     * 域名转移
     * @param $domain域名
     * @param $password域名密码
     * @param $transferOpCode操作
     * @return array
     */
    public function transferEPPDomainRequest($domain,$password,$transferOpCode){
        $array = [
            'huyiDomain'=>[
                'domain'=>$domain,
                'password'=>$password,
                'transferOpCode'=>$transferOpCode
            ],
            'trID'=>[
                'clientId'=>1
            ]
        ];
        try{
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($array,'transferEPPDomainRequest');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parsertransferEPPDomainRequest();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $message = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$message,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'transferEPPDomainRequest',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($array),$code,$data);
        return $returndata;
    }

    /**
     * 更改域名信息
     * @param $domain域名
     * @param $contactId注册人ID
     * @param $addAdminId新的管理联系人ID
     * @param $addTeachId新的技术联系人ID
     * @param $addRenewId新的续费联系人ID
     * @param $remAdminId待更改的管理联系人ID
     * @param $remTeachId待更改的技术联系人ID
     * @param $remRenewId待更改的续费联系人ID
     * @param array $addDNS新的DNS服务器
     * @param array $remDNS待删除的ns
     * @param array $remStatus待删除的域名状态
     * @param array $addStatus新的域名状态
     * @return array
     */
    public function updateEPPDomain($data){

        $newarray = [
            'huyiUpdateDomain'=>$data,
            'trID'=>[
                'clientId'=>1
            ]
        ];
        try{
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($newarray,'updateEPPDomain');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parserCommonXml();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $message = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$message,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'updateEPPDomain',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($newarray),$code,$data);
        return $returndata;
    }

    /**
     * 域名高价赎回
     * @param $domain
     * @return array
     */
    public function restoreRequestEPPDomain($domain){
        $newarray = [
            'huyiRestoreRequestDomain'=>['domain'=>$domain],
            'trID'=>[
                'clientId'=>1
            ]
        ];
        try{
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($newarray,'restoreRequestEPPDomain');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parserCommonXml();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $message = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$message,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'restoreRequestEPPDomain',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($newarray),$code,$data);
        return $returndata;
    }

    /**
     * 赎回报告操作
     * @param $domain域名
     * @param $deleteDate域名删除日期
     * @param $restoreDate域名赎回日期
     * @return array
     */
    public function restoreReportEPPDomain($domain,$deleteDate,$restoreDate){

        $array = [
            'huyiRestoreReportDomain'=>[
                'domain'=>$domain,
                'deleteDate'=>$deleteDate,
                'restoreDate'=>$restoreDate
            ],
            'trID'=>[
                'clientId'=>1
            ]
        ];
        try{
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($array,'restoreReportEPPDomain');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parserCommonXml();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $message = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$message,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'restoreRequestEPPDomain',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($array),$code,$data);
        return $returndata;
    }

    /**
     * 检查主机是否可以注册
     * @param $hosts
     */
    public function checkEPPHost($hosts){
        try{
            $hostarr = [];
            if(empty($hosts)){
                throw new Exception('参数错误','8002');
            }
            foreach((array)$hosts as $host){
                $hostarr[] = ['name'=>$host];
            }
            $array = [
                'huyiCheckHost'=>[
                    'hosts'=>$hostarr
                ],
                'trID'=>[
                    'clientId'=>1
                ]
            ];
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($array,'checkEPPHost');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parsercheckHosts();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $message = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$message,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'checkEPPHost',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($array),$code,$data);
        return $returndata;
    }

    /**
     * 创建主机
     * @param $host创建主机
     * @param array $ipv4
     * @param array $ipv6
     * @return array
     */
    public function createEPPHost($host,$ipv4 = [],$ipv6 = []){
        try{
            $array = [
                'huyiCreateHost'=>[
                    'host'=>$host
                ],
                'trID'=>[
                    'clientId'=>1
                ]
            ];
            $ips = [];
            if(!empty($ipv4)){
                foreach($ipv4 as $ip){
                    $ips[] = ['ip'=>$ip];
                }
            }
            if(!empty($ips)){
                $array['huyiCreateHost']['ipv4'] = $ips;
            }
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($array,'createEPPHost');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parsercheckHosts();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $message = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$message,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'createEPPHost',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($array),$code,$data);
        return $returndata;
    }

    /**
     * 查询主机信息
     * @param $host 主机
     * @return array
     */
    public function infoEPPHost($host){
        try{
            $array = [
                'huyiInfoHost'=>[
                    'host'=>$host
                ],
                'trID'=>[
                    'clientId'=>1
                ]
            ];
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($array,'infoEPPHost');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parserinfoEPPHost();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $message = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$message,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'infoEPPHost',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($array),$code,$data);
        return $returndata;
    }

    /**
     * 更新主机
     * @param $host
     * @param array $remIp ip,type的格式
     * @param array $addIp
     * @param array $remStatus
     * @param array $addStatus
     * @return array
     */
    public function updateEPPHost($host,$remIp = [],$addIp = [],$remStatus = [],$addStatus = []){
        try{
            $array = [
                'huyiUpdateHost'=>[
                    'host'=>$host
                ],
                'trID'=>[
                    'clientId'=>1
                ]
            ];
            if(!empty($remIp)){
                $array['huyiUpdateHost']['remIP'] = $remIp;
            }
            if(!empty($addIp)){
                $array['huyiUpdateHost']['addIp'] = $addIp;
            }
            if(!empty($remStatus)){
                $status = [];
                foreach($remStatus as $rem){
                    $status[] = ['status'=>$rem];
                }
                $array['huyiUpdateHost']['remStatus'] = $status;
            }
            if(!empty($addStatus)){
                $status2 = [];
                foreach($addStatus as $rem){
                    $status2[] = ['status'=>$rem];
                }
                $array['huyiUpdateHost']['addStatus'] = $status2;
            }
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($array,'updateEPPHost');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parserCommonXml();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $message = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$message,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'updateEPPHost',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($array),$code,$data);
        return $returndata;
    }

    /**
     * 删除主机
     * @param $host
     * @return array
     */
    public function deleteEPPHost($host){
        try{
            $array = [
                'huyiDeleteHost'=>[
                    'host'=>$host
                ],
                'trID'=>[
                    'clientId'=>1
                ]
            ];
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($array,'deleteEPPHost');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parserCommonXml();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $message = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$message,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'deleteEPPHost',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($array),$code,$data);
        return $returndata;
    }
    /**
     * check域名是否存在
     * @param $domain
     * @return string
     */
    public function checkEPPDomainLaunchClaims($domain){
        $array = [
            'huyiCheckLaunchClaimsDomain'=>['domain'=>$domain],
            'trID'=>[
                'clientId'=>Service::create_guid()
            ]
        ];
        try{
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($array,'checkEPPDomainLaunchClaims');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parserDomainLaunchClaimsXml();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $data = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$data,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'checkEPPDomainLaunchClaims',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($array),$code,$data);
        return $returndata;
    }

    /**
     * 获取TMDB的Notice信息
     * @param $tmdbId
     * @param $tmdbPw
     * @param $key
     * @return string
     */
    public function queryNoticeDomain($tmdbId,$tmdbPw,$key){
        $array = [
            'huyiQueryNoticeDomain'=>['tmdbId'=>$tmdbId,'tmdbPw'=>$tmdbPw,'key'=>$key],
            'trID'=>[
                'clientId'=>Service::create_guid()
            ]
        ];
        try{
            $eppRequest = new EPPRequest($this->epp_id,$this->pw);
            $sendxml = $eppRequest->buildXmlStart($array,'queryNoticeDomain');
            $data = $this->SendData($sendxml);
            $parser =  new EPPParser($data);
            $parserdata = $parser->parserqueryNoticeDomainXml();
            $code = $parserdata['code'];
            if($parserdata['status']=='SUCCESS'){
                $returndata = $this->return_data('ok',$parserdata);
            }else{
                $returndata = $this->return_data('err',$parserdata);
            }
        }catch(Exception $e){
            $code = $e->getCode();
            $data = $e->getMessage();
            $returndata = $this->return_data('err',array('message'=>$data,'code'=>$code));
        }
        $uid = $this->uid;
        EppOperationLog::insert($uid,'queryNoticeDomain',$this->hostname,$this->port,isset($sendxml)?$sendxml:json_encode($array),$code,$data);
        return $returndata;
    }
    /**
     * 返回信息
     * @param $type
     * @param $data
     * @return string
     */
    public static function return_data($type,$data){
        $return_data = [
            'info'=>$type,
            'data'=>$data
        ];
        return $return_data;
    }

    /**
     * 发送接口
     * @param $sendxml
     * @return array|string
     */
    public  function SendData($sendxml){
        try{
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            $connection = socket_connect($socket, $this->hostname, $this->port);
            if($connection<0){
                Yii::error('接口连接错误','epp');
                throw new Exception('接口连接错误','8004');

            }
            if( !socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,array("sec"=>60, "usec"=>0 ) ) ){
                return array('code'=>0, 'message'=>'请求超时');
            }

            if( !socket_set_option($socket,SOL_SOCKET,SO_SNDTIMEO,array("sec"=>60, "usec"=>0 ) ) ){
                return array('code'=>0, 'message'=>'接受数据超时');
            }

            Yii::error($sendxml."\r\n",'epp');
            socket_write($socket, $sendxml."\r\n",strlen( $sendxml."\r\n"));
            $result=socket_read($socket,2048);
            socket_close($socket);
            return  $result;
        }catch(Exception $e){
            return array('message'=>$e->getMessage(),'code'=>$e->getCode());
        }
    }

    public function whois($domain){
        try{
            $socket_ip = 'whois.gtld.knet.cn';
            $socket_port = 43;

            if( ! $socket = socket_create ( AF_INET, SOCK_STREAM, SOL_TCP ) ){
                return array('code'=>0,'message'=>'创建socket失败');
            }
            if( ! $connect_status = socket_connect ( $socket,$socket_ip,$socket_port)){
                return array('code'=>0,'message'=>'连接socket 服务器'.$socket_ip.' 端口'.$socket_port.'失败');

            }
            $puny = new  Ext_IdnaConvert();
            $domain = $puny->encode($domain);

            if( !$domain ){
                return array('code'=>0,'message'=>'访问socket失败，参数为空');
            }
            $chars = $domain."\n";

            if( !socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,array("sec"=>5, "usec"=>0 ) ) ){
                return array('code'=>0, 'message'=>'请求超时','query_str'=>$chars);
            }

            if( !socket_set_option($socket,SOL_SOCKET,SO_SNDTIMEO,array("sec"=>10, "usec"=>0 ) ) ){
                return array('code'=>0, 'message'=>'接受数据超时','query_str'=>$chars);
            }
            if(!socket_write ( $socket,$chars)){
                return array('code'=>0, 'message'=>'请求命令失败','query_str'=>$chars);
            }
            $response = "";
            do{
                $buf = @socket_read($socket, 2048);
                if(!$buf){ break; }
                $response.=$buf;
            }while(true);

            if(!empty($response)){
                return ['code'=>1,'message'=>$response,'query_str'=>$chars];
            }else{
                return array('code'=>0, 'message'=>'未接收到数据','query_str'=>$chars);
            }

        }catch(Exception $e){
            return array('code'=>0, 'message'=>'接口请求出错');
        }
    }
} 