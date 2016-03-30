<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014-12-23
 * Time: 14:50
 */

class EppErrorCode {

    public static $errorcode = [
        '1000'=>'成功',
        '1001'=>'命令执行成功',
        '1300'=>'命令执行成功，无结果返回',
        '1301'=>'',
        '1500'=>'',
        '2000'=>'未知命令',
        '2001'=>'命令格式错误',
        '2002'=>'命令使用错误',
        '2003'=>'缺失必要参数',
        '2005'=>'参数错误',
        '2100'=>'协议版本错误',
        '2101'=>'Unimplemented command',
        '2102'=>'Unimplemented option',
        '2103'=>'Unimplemented extension',
        '2104'=>'Billing failure',
        '2105'=>'Object is not eligible for renewal',
        '2106'=>'Object is not eligible for transfer',
        '2200'=>'Authentication error',
        '2201'=>'Authorization error',
        '2202'=>'Invalid authorization information',
        '2300'=>'Object pending transfer',
        '2301'=>'Object not pending transfer',
        '2302'=>'Object exists',
        '2303'=>'Object does not exist',
        '2304'=>'Object status prohibits operation',
        '2305'=>'Object association prohibits operation',
        '2306'=>'Parameter value policy error',
        '2307'=>'Unimplemented object service',
        '2308'=>'Data management policy violation',
        '2400'=>'Command failed',
        '2500'=>'Command failed; server closing connection',
        '2501'=>'Authentication error; server closing connection',
        '2502'=>'Session limit exceeded; server closing connection',
        '8001'=>'文件格式错误',
        '8002'=>'参数错误',
        '8004'=>'接口连接错误',
        '9000'=>'Unknown command',
        '9001'=>'Unknown protype',
        '9002'=>'Invalid parameter',
        '9003'=>'Invalid user or password',
        '9999'=>'Unknown error',
    ];
} 