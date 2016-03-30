<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014-12-19
 * Time: 17:35
 */
    namespace itm\epp;

class EPPRequest {

    protected $epp_id = '';
    protected $pw = '';

    public function __construct($epp_id = null,$pw = null) {
        $this->epp_id = null == $epp_id ? 'huyi1' : $epp_id;
        $this->pw = null == $pw ? '123456' : $pw;
    }

    public  function buildXmlStart($array,$command){
        //$pw = base64_encode(Security::encrypt($this->pw,'IDcqXMG9R6tp5Vsq'));
        $start = [
            'base'=>[
                'command'=>$command,
                'id'=>$this->epp_id,
                'proType'=>'trademark',
                'pw'=>$this->pw
            ]
        ];
        $newarray = array_merge($start,$array);
        $xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><huyiCommand></huyiCommand>");
        self::array_to_xml($newarray, $xml);
        return $xml->asXML();

    }

    /**
     * 生成xml信息
     * @param $array
     * @param $xml
     */
    public static function array_to_xml($array, &$xml) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                if(!is_numeric($key)){
                    $subnode = $xml->addChild("$key");
                    self::array_to_xml($value, $subnode);
                } else {
                    self::array_to_xml($value, $xml);
                }
            } else{
                $value = is_null($value) ? null : htmlspecialchars($value, ENT_XML1, 'UTF-8');
                $xml->addChild("$key","$value");
            }
        }
    }
} 