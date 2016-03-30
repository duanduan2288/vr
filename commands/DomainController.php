<?php
    /**
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2015-9-17
     * Time: 18:13
     */

    namespace app\commands;

    use app\models\CustomerFeedback;
    use app\models\Service;
    use Yii;
    use app\models\DataBusiness;
    use itm\epp\EPP;
    use app\models\DomainFeedback;
    use yii\console\Controller;
    use yii\db\Expression;
    use app\models\HelpAudit;

    class DomainController extends Controller
    {
        public function actionIndex(){

            $data = $this->get_success_domains();
            $arr = $data['domains'];
            $i=0;
            $data = [];
            $epp = new EPP();
            foreach($arr as $value){
                $returndata = $epp->whois(strtolower($value['domain']));
                if(!empty($returndata) && $returndata['code']==1 && $returndata['message']!='No match'){
                    $domaininfo = explode("\r\n", $returndata['message']);
                    $name_flag = $this->checkArray('Registrant Name:',$domaininfo);
                    $company_flag = $this->checkArray('Registrant Organization:',$domaininfo);
                    $phone_flag = $this->checkArray('Registrant Phone:',$domaininfo);
                    $contact_flag = $this->checkArray('Registrant ID:',$domaininfo);
                    $email_flag = $this->checkArray('Registrant Email:',$domaininfo);
                    $address_flag = $this->checkArray('Creation Date:',$domaininfo);
                    $name = $company = $phone = $email = $address = $contact_id = '';
                    if ($contact_flag['info']=='ok') {
                        $contacts = explode(':', $domaininfo[$contact_flag['k']]);
                        if (isset($contacts[1])) {
                            $contact_id = $contacts[1] ;
                        }
                    }
                    if ($name_flag['info']=='ok') {
                        $names = explode(':', $domaininfo[$name_flag['k']]);
                        if (isset($names[1])) {
                            $name = $names[1] ;
                        }
                    }
                    if ($company_flag['info']=='ok') {
                        $companys = explode(':', $domaininfo[$company_flag['k']]);
                        if (isset($companys[1])) {
                            $company = $companys[1] ;
                        }
                    }
                    if ($phone_flag['info']=='ok') {
                        $phones = explode(':', $domaininfo[$phone_flag['k']]);
                        if (isset($phones[1])) {
                            $phone = $phones[1] ;
                        }
                    }
                    if ($email_flag['info']=='ok') {
                        $emails = explode(':', $domaininfo[$email_flag['k']]);
                        if (isset($emails[1])) {
                            $email = $emails[1] ;
                        }
                    }
                    if ($address_flag['info']=='ok') {
                        $address = str_replace('Creation Date:','',$domaininfo[$address_flag['k']]);
                    }
                    $feedback = new DomainFeedback();
                    $feedback->guid = Service::create_guid();
                    $feedback->domain = $value['domain'];
                    $feedback->resigtrant_name = $name;
                    $feedback->registrant_organization = $company;
                    $feedback->contact_id = $contact_id;
                    $feedback->telephone = $phone;
                    $feedback->email = $email;
                    $feedback->service_start_time = date('Y-m-d H:i:s',strtotime($address));
                    $feedback->contact_modified = new Expression('NOW()');
                    $feedback->operator_id = 0;
                    $feedback->created = new Expression('NOW()');
                    $feedback->modified = new Expression('NOW()');
                    $flag = $feedback->save();
                    if(!$flag){
                        Yii::error(json_encode($feedback->errors,JSON_UNESCAPED_UNICODE),'command');
                    }
                    echo $address."\r\n";
                }
                $i++;
                if($i%10==0){
                    sleep(1);
                }
            }
            //array_unshift($data,array('注册商名称','域名名称','域名所有者名称','域名所有者公司','域名所有者电话','域名所有者邮箱','域名所有者地址'));
            //$filename = iconv('UTF-8','GBK//IGNORE','所有域名信息列表');
            //$this->wirte_excel($data,$filename);
        }

        /**************************************************************************************************************/

        public function actionFeedback(){
            $data = DomainFeedback::find()->groupBy('registrant_name')->all();
            foreach($data as $info){
                $customer = new CustomerFeedback();
                $customer->guid = Service::create_guid();
                $customer->registrant_name = $info->registrant_name;
                $customer->registrant_organization = $info->registrant_organization;
                $customer->contact_id = $info->contact_id;
                $customer->telephone = $info->telephone;
                $customer->email = $info->email;
                $customer->domain_count = 0;
                $customer->feedback_status = '待回访';
                $customer->operator_id = 0;
                $customer->update_flag = '否';
                $customer->created = new Expression('NOW()');
                $customer->modified = new Expression('NOW()');
                $customer->save();
            }
        }
        /**
         * 把数据保存到EXCEL
         */
        public  function wirte_excel($arrDataList,$title){
            $phpExcelPath = Yii::getPathOfAlias("ext.phpexcel.Classes");
            spl_autoload_unregister(array('YiiBase','autoload'));
            require_once ($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
            if(!empty($arrDataList)){
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->getProperties()->setCreator("注册局")
                    ->setLastModifiedBy("注册局")
                    ->setTitle($title);

                //$arrExcelInfo = eval('return ' . iconv('gbk', 'utf-8', var_export($arrDataList, true)) . ';'); //将数组转换成utf-8
                $objPHPExcel->getActiveSheet()->fromArray(
                    $arrDataList, // 赋值的数组
                    NULL, // 忽略的值,不会在excel中显示
                    'A1' // 赋值的起始位置
                );
                $path = Yii::app()->params['upload']['attachment_root_dir'] . '/domains_analytic/'.date('Y');
                !file_exists($path) ? mkdir($path,'0755',true) : '';
                $xlsfilename = $title.date('Y-m-d').'.xls';
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save($path . '/' . $xlsfilename);
                echo '--wirteDatatoExcel end ok --<br>';
            }
            spl_autoload_register(array('YiiBase','autoload'));
        }

        /**
         * 成功注册域名个数
         * @param string $currentstart
         * @param string $currentend
         * @param string $registrar_name
         * @param string $condition
         * @return array
         */
        public static function get_success_domains($currentstart='',$currentend='',$registrar_name='',$condition=''){
            $arr = $year = $cost = [];
            $c = "1=1";
            $sqlarr = "`audit_category`='命名审核' AND `audit_result`='审核通过'";
            //注册商组
            if(!empty($condition)){
                $c .= ' AND '.$condition;
            }
            //日期筛选
            if(!empty($currentstart) && !empty($currentend)){
                //$c .= " AND `operation_date`>='".$currentstart."' AND `operation_date`<='".$currentend."'";
                $sqlarr .= " AND `audit_time`>='".$currentstart."' AND `audit_time`<='".$currentend."'";
            }
            //注册商筛选
            //if(!empty($registrar_name)){
            //    $registrar = Registrar::model()->findByAttributes(['abbreviation'=>trim($registrar_name),'status'=>'正常']);
            //    if(null!==$registrar){
            //        $sqlarr .= "AND `registrar_id`='".$registrar->epp_id."'";
            //    }
            //    $c .= " AND `registrar_name`='".$registrar_name."'";
            //}
            $sql = "select count(*) as count,`domain_name`,`cost_type`,`operation_date`,`operation_deadline`,`cost`,`registrar_name` from (select * from data_finance WHERE operation_type = '域名创建费用' AND ".$c." order by id desc) t group by domain_name";

            $data = Yii::$app->db->createCommand($sql)->queryAll();

            foreach($data as $value){

                $str = "`domain_name`='".$value['domain_name']."'  AND ".$sqlarr;

                $status = DataBusiness::find()->where($str)->one();

                if('扣款'==$value['cost_type'] && null!==$status){
                    $arr[] = [
                        'registrar_name'=>$value['registrar_name'],
                        'domain'=>$value['domain_name'],
                        'registrant_organization'=>$status['registrant_organization'],
                        'years'=>$value['operation_deadline'],
                        'costs'=>$value['cost'],
                        'cost_type'=>$value['cost_type'],
                        'audit_result'=>$status['audit_result'],
                        'audit_time'=>$status['audit_time']
                    ];
                    $year[] = $value['operation_deadline'];
                    $cost[] = $value['cost'];
                }
            }
            return ['domains'=>$arr,'years'=>$year,'costs'=>$cost];
        }

        private function checkArray($need,$arr)
        {
            $return = array('info'=>'error','k'=>'');
            foreach( $arr as $k=>$v ){
                if ( strpos( $v , $need ) !== false )
                {
                    $return = array('info'=>'ok','k'=>$k);break;
                }
            }
            return $return;
        }
    }