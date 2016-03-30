<div class="row">
    <div class="col-md-12">
        <div class="portlet">
        <div class="portlet-title">
            <div class="caption"><i class="icon icon-cog"></i>公司详细信息</div>
            <div class="tools">
                <?php if($model->status!='审核中'):?>
                <a href="<?php echo $this->createUrl('/registrar/createcompany?id='.$model->id); ?>"><i class="icon-plus"></i>修改</a>&nbsp;&nbsp;
                <?php endif;?>
                <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>
            </div>

        </div>
            </div>
        <div class="form">
            <?php if(!empty($issue)): ?>
                <?php $typeName = strtolower($issue->type);?>
                <?php $this->renderPartial('types/'.$typeName, array('issue' => $issue));?>

            <?php endif; ?>
        </div>
        <table id="user" class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <td style="width:25%">
                        注册商代码
                    </td>
                    <td style="width:75%">
                        <span class="text-muted"><?php echo $model->code;?></span>
                    </td>
                </tr>
                <tr>
                    <td style="width:25%">
                        单位简称
                    </td>
                    <td style="width:75%">
                        <span class="text-muted"><?php echo $model->abbreviation;?></span>
                    </td>
                </tr>
                <tr>
                    <td style="width:25%">
                        单位名称
                    </td>
                    <td style="width:75%">
                        <span class="text-muted"><?php echo $model->company_name_zh_cn;?></span>
                    </td>
                </tr>
                <tr>
                    <td style="width:25%">
                        URL
                    </td>
                    <td style="width:75%">
                        <span class="text-muted"><?php echo $model->company_url;?></span>
                    </td>
                </tr>
                <tr>
                    <td style="width:25%">
                        行业ID
                    </td>
                    <td style="width:75%">
                        <span class="text-muted"><?php echo $model->industry_type;?></span>
                    </td>
                </tr>
                <tr>
                    <td style="width:25%">
                        创建时间
                    </td>
                    <td style="width:75%">
                        <span class="text-muted"><?php echo $model->created;?></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
