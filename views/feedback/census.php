<link href="/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
<?php
    use yii\widgets\LinkPager;
    use app\models\Service;
    use yii\helpers\Url;
?>
<style>
    .table th a{ color:#000;text-decoration:none;}
    .table td a{text-decoration:none;}
    .input-small {width: 250px !important;}
    .table .asc {
        background: rgba(0, 0, 0, 0) url("/img/asc.png") no-repeat scroll right center;
        padding-right: 15px;
    }
    .table .desc {
        background: rgba(0, 0, 0, 0) url("/img/desc.png") no-repeat scroll right center;
        padding-right: 15px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>域名回访管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">客户回访统计</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" id="search_form" role="form" method="get">
                    <div class="form-body">
                        <div class="form-group">
                            <select  name="registrar_id" class="form-control input-small">
                                <option value="">请选择注册商</option>
                                <?php foreach($registrars as $key=>$registrarname):?>
                                    <option value="<?php echo $key;?>" <?php echo $registrar_id==$key?'selected':''; ?>>
                                        <?php echo $registrarname;?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <select  name="agent_code" class="form-control input-small">
                                <option value="">请选择代理商</option>
                                <?php foreach($agents as $key=>$agent_name):?>
                                    <option value="<?php echo $key;?>" <?php echo $key==$agent_code?'selected':''; ?>>
                                        <?php echo $agent_name;?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <button class="btn blue" type="submit"><i class="icon-search"></i> 查询</button>
                        <button id="order_download" class="btn blue" type="button"><i class="icon-download"></i> 导出EXCEL</button>
                    </div>
                </form>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-hover table-bordered table-advance">
                    <thead>
                    <tr>
                        <th>注册商</th>
                        <th>代理商</th>
                        <th>域名</th>
                        <th>是否一次通过</th>
                        <th>修改次数</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($data) { ?>
                        <?php foreach($data as $list) { ?>
                            <tr>
                                <td>
                                    <?php echo Service::get_company_name_by_id($list['registrar_id'])?>
                                </td>
                                <td>
                                    <?php
                                        $code = Service::get_agent_code_by_contact($list['contact_id']);
                                        echo Service::get_agent_name_by_code($code);
                                    ?>
                                </td>
                                <td><?php echo $list['domain'];?></td>
                                <td>
                                    <?php echo $list['update_count']>0?'否':'是';?>
                                </td>
                                <td><?php echo $list['update_count'];?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td align="center" colspan="5">无记录</td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="pull-right">
                    <?php
                        echo LinkPager::widget([
                            'pagination' => $pages,
                        ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('#order_download').click(function(event) {
            var data = $('#search_form').serialize();
            window.location.href = '/export/census?'+data+'&dtype=excel';
        });
    });
</script>