<style>
.table th a{ color:#000;text-decoration:none;}
.table td a{text-decoration:none;}
.input-small {width: 250px !important;}
.calendar-time{display: none;}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                        <i class="icon icon-cog"></i>
                        <a>信息管理&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                        <a href="#">邮件发送列表</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="/kb"><i class="icon-question"></i>帮助</a>
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" role="form" method="get">
                    <div class="form-body">
                        <div class="form-group">
                            <input type="text" class="form-control input-small" name="domain" placeholder="域名" value="<?php echo $domain; ?>">
                        </div>
                        <button class="btn blue" type="submit"><i class="icon-search"></i> 查询</button>
                    </div>
                </form>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-hover table-bordered table-advance">
                    <thead>
                        <tr>
                            <th>域名</th>
                            <th>收件人</th>
                            <th width="30%">内容</th>
                            <th>状态</th>
                            <th>失败原因</th>
                            <th>操作人</th>
                            <th>发送时间</th>
                            <th width="10%">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lists as $list) : ?>
                            <tr>
                                <td><?php echo $list['domain']?></td>
                                <td><?php if(!empty($list['to'])){
                                        $emails = json_decode($list['to'],true);
                                        echo implode('，',$emails);
                                    }?>
                                </td>
                                <td width="30%"><?php echo strip_tags($list['body']);?></td>
                                <td><?php echo $list['status']?></td>
                                <td><?php echo $list['fail_reason']?></td>
                                <td><?php echo Service::get_user_name($list['creator']);?></td>
                                <td><?php echo $list['created']?></td>
                                <td width="10%">
                                    <a class="delete del" href="javascript:;" data-id="<?php echo $list['id']; ?>">重新发送</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="pull-right">
                    <?php
                        $this->widget('CLinkPager',array(
                            'htmlOptions' => array('class' => 'pagination'),
                            'header'=>'',
                            'firstPageLabel' => '首页',
                            'lastPageLabel' => '末页',
                            'prevPageLabel' => '上一页',
                            'nextPageLabel' => '下一页',
                            'pages' => $pages,
                            'maxButtonCount'=>13
                        ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
		$(function(){

            $('.portlet-body').on('click','.del',function(event) {
                var id = $(this).attr('data-id');
                if(id==''){
                    layer.msg('参数错误',2,3);
                    return;
                }
                layer.confirm('确定重新发送吗', function(){
                    var loadi = layer.load('正在处理中，请稍后…');
                    $.ajax({
                        type: 'POST',
                        url: '/sendMessage/resendEmail',
                        data: {'id':id},
                        dataType:'json',
                        success: function(json) {
                            layer.close(loadi);
                            if (json.info != 'ok') {
                                layer.msg('发送失败',2,3);
                                return false;
                            }
                            layer.msg('发送成功', 2, 1, function(){window.location.reload();});
                        }
                    });
                });
            });
        })
</script>