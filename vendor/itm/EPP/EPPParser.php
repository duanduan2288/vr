<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014-12-22
 * Time: 13:43
 */
    namespace itm\epp;

use app\extensions\Ext_IdnaConvert;
class EPPParser {

    protected  $xelements;

    public function __construct($resultXml) {
        $this->xelements = simplexml_load_string($resultXml);
    }

    /**
     * 检查域名是否已经注册
     * @return array
     * @throws Exception
     */
    public function parsercheckdomain(){
        if(isset($this->xelements->code)){
            $code = (int)$this->xelements->code;
            if($code<2000 && $code>=1000){
                $returndata = [];
                $punycode = new Ext_IdnaConvert();
                $domains = $this->xelements->info->domains->domain;
                if(!empty($domains)){
                    foreach($domains as $domain){
                        $avail = (int)$domain->avail;
                        $name = $punycode->decode((string)$domain->name);
                        $reason = isset($domain->reason)?(string)$domain->reason:'';
                        $returndata[$name] = ['avail'=>$avail,'name'=>$name,'reason'=>$reason];
                    }
                }
                $returndata['code'] = $code;
                $returndata['status'] = 'SUCCESS';

            }else{
                //解析错误格式
                $message = (string)$this->xelements->message;
                $returndata = array('message'=>$message,'code'=>$code,'status'=>'ERROR');
            }
            if(isset($this->xelements->reason)){
                $returndata['reason'] = (string)$this->xelements->reason;
            }
            $trId = $this->xelements->trID;
            if($trId){
                $returndata['clientId'] = (string)$trId->clientId;
                $returndata['serverId'] = (string)$trId->serverId;
            }
            return $returndata;

        }else{
            return array('message'=>'文件格式错误','code'=>'8001','status'=>'ERROR');
        }
    }

    /**
     * 创建新联系人
     * @return array
     * @throws Exception
     */
    public function parsercreatecontact(){
        if(isset($this->xelements->code)){
            $code = (int)$this->xelements->code;
            if($code<2000 && $code>=1000){
                $returndata = [];
                $returndata['cid'] = (string)$this->xelements->info->cid;
                $returndata['credate'] = (string)$this->xelements->info->creDate;

                $returndata['code'] = $code;
                $returndata['status'] = 'SUCCESS';
            }else{
                $message = (string)$this->xelements->message;
                $returndata = array('message'=>$message,'code'=>$code,'status'=>'ERROR');
            }
            if(isset($this->xelements->reason)){
                $returndata['reason'] = (string)$this->xelements->reason;
            }
            $trId = $this->xelements->trID;
            if($trId){
                $returndata['clientId'] = (string)$trId->clientId;
                $returndata['serverId'] = (string)$trId->serverId;
            }
            return $returndata;
        }else{
            return array('message'=>'文件格式错误','code'=>'8001','status'=>'ERROR');
        }
    }

    /**
     *
     * @return array
     */
    public function parsercheckcontact(){
        if(isset($this->xelements->code)){
            $code = (int)$this->xelements->code;
            if($code<2000 && $code>=1000){
                $returndata = [];
                $returndata['cid'] = (string)$this->xelements->info->cid;
                $returndata['avail'] = (string)$this->xelements->info->avail;
                $returndata['code'] = $code;
                $returndata['status'] = 'SUCCESS';

            }else{
                $message = (string)$this->xelements->message;
                $returndata = array('message'=>$message,'code'=>$code,'status'=>'ERROR');
            }
            if(isset($this->xelements->reason)){
                $returndata['reason'] = (string)$this->xelements->reason;
            }
            $trId = $this->xelements->trID;
            if($trId){
                $returndata['clientId'] = (string)$trId->clientId;
                $returndata['serverId'] = (string)$trId->serverId;
            }
            return $returndata;
        }else{
            return array('message'=>'文件格式错误','code'=>'8001','status'=>'ERROR');
        }
    }

    /**
     * 联系人详情
     * @return array
     */
    public function parserinfoEPPContact(){
        if(isset($this->xelements->code)){
            $code = (int)$this->xelements->code;
            if($code<2000 && $code>=1000){
                $returndata = [];
                $returndata['cid'] = (string)$this->xelements->info->cid;
                $returndata['company'] = (string)$this->xelements->info->company;
                $returndata['fname'] = (string)$this->xelements->info->fname;
                $address = $this->xelements->info->addr;
                if(count($address)>1){
                    $addarr = [];
                    foreach((array)$address as $addres){
                        $addarr[] = (string)$addres->addres;
                    }
                }else{
                    $addarr = [(string)$address->addres];
                }
                $returndata['address'] = $addarr;
                $returndata['state'] = (string)$this->xelements->info->state;
                $returndata['city'] = (string)$this->xelements->info->city;
                $returndata['postCode'] = (string)$this->xelements->info->postCode;
                $returndata['telephone'] = (string)$this->xelements->info->telephone;
                $returndata['email'] = (string)$this->xelements->info->email;
                $returndata['telphoneExt'] = (string)$this->xelements->info->telphoneExt;

                if($this->xelements->info->fax){
                    $returndata['fax'] = (string)$this->xelements->info->fax;
                }

                $returndata['country'] = (string)$this->xelements->info->country;
                $returndata['password'] = (string)$this->xelements->info->password;
                $returndata['crDate'] = (string)$this->xelements->info->crDate;

                $statuses = $this->xelements->info->statuses;
                if(count($statuses)>1){
                    $status = [];
                    foreach((array)$statuses as $state){
                        $status[] = (string)$state->status;
                    }
                }else{
                    $status = [(string)$statuses->status];
                }
                $returndata['statuses'] = $status;
                $returndata['roid'] = (string)$this->xelements->info->roid;

                $returndata['code'] = $code;
                $returndata['status'] = 'SUCCESS';
            }else{
                $message = (string)$this->xelements->message;
                $returndata = array('message'=>$message,'code'=>$code,'status'=>'ERROR');
            }
            if(isset($this->xelements->reason)){
                $returndata['reason'] = (string)$this->xelements->reason;
            }
            $trId = $this->xelements->trID;
            if($trId){
                $returndata['clientId'] = (string)$trId->clientId;
                $returndata['serverId'] = (string)$trId->serverId;
            }
            return $returndata;

        }else{
            return array('message'=>'文件格式错误','code'=>'8001','status'=>'ERROR');
        }
    }
    /**
     * 通用 删除联系人，修改联系人
     * @return array
     */
    public function parserCommonXml(){
        if(isset($this->xelements->code)){
            $code = (int)$this->xelements->code;
            if($code<2000 && $code>=1000){
                $returndata['code'] = $code;
                $returndata['status'] = 'SUCCESS';
            }else{
                $message = (string)$this->xelements->message;
                $returndata = array('message'=>$message,'code'=>$code,'status'=>'ERROR');
            }
            if(isset($this->xelements->reason)){
                $returndata['reason'] = (string)$this->xelements->reason;
            }
            $trId = $this->xelements->trID;
            if($trId){
                $returndata['clientId'] = (string)$trId->clientId;
                $returndata['serverId'] = (string)$trId->serverId;
            }
            return $returndata;

        }else{
            return array('message'=>'文件格式错误','code'=>'8001','status'=>'ERROR');
        }
    }
    /**
     * 解析创建域名
     * @return array
     */
    public function parsercreateEPPDomain(){
        if(isset($this->xelements->code)){
            $code = (int)$this->xelements->code;
            if($code<2000 && $code>=1000){
                $punycode = new Ext_IdnaConvert();
                $domain = $punycode->decode((string)$this->xelements->info->domain);
                $creDate = (string)$this->xelements->info->creDate;
                $expDate = (string)$this->xelements->info->expDate;

                $returndata = ['domain'=>$domain,'creDate'=>$creDate,'expDate'=>$expDate];
                $returndata['code'] = $code;
                $returndata['status'] = 'SUCCESS';
            }else{
                //解析错误格式
                $message = (string)$this->xelements->message;
                $returndata = array('message'=>$message,'code'=>$code,'status'=>'ERROR');
                if(isset($this->xelements->reason)){
                    $returndata['reason'] = (string)$this->xelements->reason;
                }
            }
            $trId = $this->xelements->trID;
            if($trId){
                $returndata['clientId'] = (string)$trId->clientId;
                $returndata['serverId'] = (string)$trId->serverId;
            }
            return $returndata;

        }else{
            return array('message'=>'文件格式错误','code'=>'8001','status'=>'ERROR');
        }
    }

    /**
     * 获取域名详细信息
     * @return array
     */
    public function parserinfoEPPDomain(){
        if(isset($this->xelements->code)){
            $code = (int)$this->xelements->code;
            if($code<2000 && $code>=1000){
                $returndata = [];
                $punycode = new Ext_IdnaConvert();
                $returndata['domain'] = $punycode->decode((string)$this->xelements->info->domain);
                $returndata['password'] = (string)$this->xelements->info->password;
                $returndata['contactId'] = (string)$this->xelements->info->contactId;
                $returndata['adminId'] = (string)$this->xelements->info->adminId;
                $returndata['teachId'] = (string)$this->xelements->info->teachId;
                $returndata['renewId'] = (string)$this->xelements->info->renewId;
                $returndata['regDate'] = (string)$this->xelements->info->regDate;
                $returndata['expDate'] = (string)$this->xelements->info->expDate;
                $returndata['roid'] = (string)$this->xelements->info->roid;

                if(isset($this->xelements->info->nses)){
                    $nses = $this->xelements->info->nses;
                    $nsarr = [];
                    foreach((array)$nses as $ns){
                        foreach($ns as $n){
                            $nsarr[] = (string)$n->ns;
                        }
                    }
                    $returndata['nses'] = $nsarr;
                }
                $statuses = $this->xelements->info->statuses;
                if(count($statuses)>1){
                    $status = [];
                    foreach((array)$statuses as $state){
                        $status[] = (string)$state->status;
                    }
                }else{
                    $status = [(string)$statuses->status];
                }
                $returndata['statuses'] = $status;

                $returndata['code'] = $code;
                $returndata['status'] = 'SUCCESS';
            }else{
                $message = (string)$this->xelements->message;
                $returndata = array('message'=>$message,'code'=>$code,'status'=>'ERROR');
            }
            if(isset($this->xelements->reason)){
                $returndata['reason'] = (string)$this->xelements->reason;
            }
            $trId = $this->xelements->trID;
            if($trId){
                $returndata['clientId'] = (string)$trId->clientId;
                $returndata['serverId'] = (string)$trId->serverId;
            }
            return $returndata;

        }else{
            return array('message'=>'文件格式错误','code'=>'8001','status'=>'ERROR');
        }
    }

    /**
     * 解析主机信息
     * @return array
     */
    public function parsertransferEPPDomainRequest(){
        if(isset($this->xelements->code)){
            $code = (int)$this->xelements->code;
            if($code<2000 && $code>=1000){
                $returndata = [];
                $punycode = new Ext_IdnaConvert();
                $returndata['name'] = $punycode->decode((string)$this->xelements->info->name);
                $returndata['expDate'] = (string)$this->xelements->info->expDate;
                $returndata['transferStatus'] = (string)$this->xelements->info->transferStatus;
                $returndata['requestDate'] = (string)$this->xelements->info->requestDate;
                $returndata['requestId'] = (string)$this->xelements->info->requestId;
                $returndata['acId'] = (string)$this->xelements->info->acId;
                $returndata['acDate'] = (string)$this->xelements->info->acDate;

                $returndata['code'] = $code;
                $returndata['status'] = 'SUCCESS';
            }else{
                $message = (string)$this->xelements->message;
                $returndata = array('message'=>$message,'code'=>$code,'status'=>'ERROR');
            }
            if(isset($this->xelements->reason)){
                $returndata['reason'] = (string)$this->xelements->reason;
            }
            $trId = $this->xelements->trID;
            if($trId){
                $returndata['clientId'] = (string)$trId->clientId;
                $returndata['serverId'] = (string)$trId->serverId;
            }
            return $returndata;

        }else{
            return array('message'=>'文件格式错误','code'=>'8001','status'=>'ERROR');
        }
    }

    /**
     * 检查主机是否已经注册
     * @return array
     * @throws Exception
     */
    public function parsercheckHosts(){
        if(isset($this->xelements->code)){
            $code = (int)$this->xelements->code;
            if($code<2000 && $code>=1000){
                $returndata = [];
                $punycode = new Ext_IdnaConvert();
                $hosts = $this->xelements->info->hosts->host;
                if(!empty($hosts)){
                    foreach($hosts as $host){
                        $avail = (int)$host->avail;
                        $name = $punycode->decode((string)$host->name);
                        $reason = isset($host->reason)?(string)$host->reason:'';
                        $returndata[$name] = ['avail'=>$avail,'name'=>$name,'reason'=>$reason];
                    }
                }
                $returndata['code'] = $code;
                $returndata['status'] = 'SUCCESS';

            }else{
                //解析错误格式
                $message = (string)$this->xelements->message;
                $returndata = array('message'=>$message,'code'=>$code,'status'=>'ERROR');
            }
            if(isset($this->xelements->reason)){
                $returndata['reason'] = (string)$this->xelements->reason;
            }
            $trId = $this->xelements->trID;
            if($trId){
                $returndata['clientId'] = (string)$trId->clientId;
                $returndata['serverId'] = (string)$trId->serverId;
            }
            return $returndata;

        }else{
            return array('message'=>'文件格式错误','code'=>'8001','status'=>'ERROR');
        }
    }

    /**
     * 解析主机信息
     * @return array
     */
    public function parserinfoEPPHost(){
        if(isset($this->xelements->code)){
            $code = (int)$this->xelements->code;
            if($code<2000 && $code>=1000){
                $returndata = [];
                $punycode = new Ext_IdnaConvert();
                $returndata['host'] = $punycode->decode((string)$this->xelements->info->host);
                $returndata['creDate'] = (string)$this->xelements->info->creDate;
                $returndata['lastUpdatedDate'] = (string)$this->xelements->info->lastUpdatedDate;
                $returndata['createdBy'] = (string)$this->xelements->info->createdBy;
                $returndata['roid'] = (string)$this->xelements->info->roid;
                $returndata['trDate'] = (string)$this->xelements->info->trDate;
                if(isset($this->xelements->info->ipv4)){
                    $ipv4s = $this->xelements->info->ipv4;
                    if(count($ipv4s)>1){
                        $ipv4arr = [];
                        foreach((array)$ipv4s as $ipv4){
                            $ipv4arr[] = (string)$ipv4->ip;
                        }
                    }else{
                        $ipv4arr = (string)$ipv4s->ip;
                    }
                    $returndata['ipv4'] = $ipv4arr;
                }
                if(isset($this->xelements->info->ipv6)){
                    $ipv6s = $this->xelements->info->ipv6;
                    if(count($ipv6s)>1){
                        $ipv6arr = [];
                        foreach((array)$ipv6s as $ipv6){
                            $ipv6arr[] = (string)$ipv6->ip;
                        }
                    }else{
                        $ipv6arr = (string)$ipv6s->ip;
                    }
                    $returndata['ipv6'] = $ipv6arr;
                }
                $statuses = $this->xelements->info->statuses;
                if(count($statuses)>1){
                    $status = [];
                    foreach((array)$statuses as $state){
                        $status[] = (string)$state->status;
                    }
                }else{
                    $status = [(string)$statuses->status];
                }
                $returndata['statuses'] = $status;

                $returndata['code'] = $code;
                $returndata['status'] = 'SUCCESS';
            }else{
                $message = (string)$this->xelements->message;
                $returndata = array('message'=>$message,'code'=>$code,'status'=>'ERROR');
            }
            if(isset($this->xelements->reason)){
                $returndata['reason'] = (string)$this->xelements->reason;
            }
            $trId = $this->xelements->trID;
            if($trId){
                $returndata['clientId'] = (string)$trId->clientId;
                $returndata['serverId'] = (string)$trId->serverId;
            }
            return $returndata;

        }else{
            return array('message'=>'文件格式错误','code'=>'8001','status'=>'ERROR');
        }
    }
    /**
     * 解析日升期查询域名是否存在
     * @return array
     */
    public function parserDomainLaunchClaimsXml(){
        if(isset($this->xelements->code)){
            $code = (int)$this->xelements->code;
            if($code<2000 && $code>=1000){
                $punycode = new Ext_IdnaConvert();
                $returndata['domain'] = $punycode->decode((string)$this->xelements->info->domain);
                $returndata['key'] = (string)$this->xelements->info->key;
                $returndata['code'] = $code;
                $returndata['status'] = 'SUCCESS';
            }else{
                $message = (string)$this->xelements->message;
                $returndata = array('message'=>$message,'code'=>$code,'status'=>'ERROR');
            }
            if(isset($this->xelements->reason)){
                $returndata['reason'] = (string)$this->xelements->reason;
            }
            $trId = $this->xelements->trID;
            if($trId){
                $returndata['clientId'] = (string)$trId->clientId;
                $returndata['serverId'] = (string)$trId->serverId;
            }
            return $returndata;
        }
    }

    /**
     * 解析日升期查询域名是否存在
     * @return array
     */
    public function parserqueryNoticeDomainXml(){
        if(isset($this->xelements->code)){
            $code = (int)$this->xelements->code;
            if($code<2000 && $code>=1000){
                $returndata['noticeId'] = (string)$this->xelements->info->noticeId;
                $returndata['notAfterDate'] = (string)$this->xelements->info->notAfterDate;
                $returndata['acceptedDate'] = (string)$this->xelements->info->acceptedDate;
                $returndata['code'] = $code;
                $returndata['status'] = 'SUCCESS';
            }else{
                $message = (string)$this->xelements->message;
                $returndata = array('message'=>$message,'code'=>$code,'status'=>'ERROR');
            }
            if(isset($this->xelements->reason)){
                $returndata['reason'] = (string)$this->xelements->reason;
            }
            $trId = $this->xelements->trID;
            if($trId){
                $returndata['clientId'] = (string)$trId->clientId;
                $returndata['serverId'] = (string)$trId->serverId;
            }
            return $returndata;
        }
    }

} 