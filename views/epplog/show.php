<?php
use app\models\Service;
?>
<style>
    .table-striped > tbody > tr:nth-child(2n+1) > td.info-left {background-color: #ccc;}
    td span{padding-left:200px;}
    .info-left{background-color: #ccc;text-align: right;}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a href="/epplog">EppLog列表&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                    <a href="#">EppLog详情</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>
        </div>
        <style>
            td span.text-muted{ padding-left: 20px;}
        </style>
        <div class="col-md-7" style="padding-left: 0">
        <table id="user" class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <td style="width:200px;" class="info-left">EppLogID：</td>
                    <td>
                        <span class="text-muted"><?php echo $info['id']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="info-left">Command：</td>
                    <td>
                        <span class="text-muted"><?php echo $info['command']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="info-left">接口IP：</td>
                    <td>
                        <span class="text-muted"><?php echo $info['interface_ip']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="info-left">接口端口：</td>
                    <td>
                        <span class="text-muted"><?php echo $info['interface_port']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="info-left">发送内容：</td>
                    <td>
                        <span class="text-muted"><?php echo htmlspecialchars($info['send_content']); ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="info-left">发送日期：</td>
                    <td>
                        <span class="text-muted"><?php echo $info['send_date']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="info-left">返回代码：</td>
                    <td>
                        <span class="text-muted"><?php echo $info['code']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="info-left">接收内容：</td>
                    <td>
                        <span class="text-muted"><?php echo htmlspecialchars($info['receive_content']); ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="info-left">接收日期：</td>
                    <td>
                        <span class="text-muted"><?php echo $info['receive_date']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="info-left">所属公司：</td>
                    <td>
                        <span class="text-muted"><?php echo Service::get_company_name_by_id($info['company_id']); ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="info-left">操作人：</td>
                    <td>
                        <span class="text-muted"><?php echo Service::get_user_name($info['creator']); ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="info-left">操作人IP：</td>
                    <td>
                        <span class="text-muted"><?php echo $info['creator_ip']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="info-left">操作时间：</td>
                    <td>
                        <span class="text-muted"><?php echo $info['created']; ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>
</div>