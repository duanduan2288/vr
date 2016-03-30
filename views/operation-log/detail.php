<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>系统管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">操作日志详情</a>
                </div>
                <div class="tools">
                    <!--<a href="--><?php //echo $this->createUrl('/issue/create/'); ?><!--"><i class="icon-plus"></i> 添加</a>-->
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>

            <div class="portlet-body">
                <ul class="nav nav-tabs">
                </ul>
                <!--修改前-->
                <h4>修改前</h4>

                <table class="table table-striped table-hover table-bordered table-advance">

                    <tr><th>字段名称</th><th>值</th></tr>
                    <?php if(!empty($models['before_edit'])): ?>
                    <?php
                        $befores = json_decode($models['before_edit'],true);
                        foreach($befores as $key=>$value):
                        ?>
                        <tr>
                            <td><?php echo $key;?></td>
                            <td><?php echo $value;?></td>
                        </tr>
                    <?php endforeach;?>
                    <?php else:?>
                        <tr><td colspan="2">暂无数据</td></tr>
                    <?php endif;?>
                </table>
                <hr>
                <h4>修改后</h4>

                <table class="table table-striped table-hover table-bordered table-advance">
                    <tr><th>字段名称</th><th>值</th></tr>
                    <?php if(!empty($models['after_edit'])): ?>
                    <?php
                        $befores = json_decode($models['after_edit'],true);
                        foreach($befores as $key=>$value):
                            ?>
                            <tr>
                                <td><?php echo $key;?></td>
                                <td><?php echo is_string($value)?$value:'';?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td colspan="2">暂无数据</td></tr>
                    <?php endif;?>
                </table>
            </div>
        </div>
    </div>
</div>