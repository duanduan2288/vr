<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014-12-26
 * Time: 13:53
 */
namespace app\models;
use SoapClient;
class ZSoapClient extends SoapClient
{
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $response = parent::__doRequest($request, $location, $action, $version, $one_way);

        //根据实际情况做处理。。。，如果是<soap开头，改成<?xml
        $start=strpos($response,'<soap');
        $end=strrpos($response,'>');
        $response_string=substr($response,$start,$end-$start+1);
        return($response_string);
    }
}