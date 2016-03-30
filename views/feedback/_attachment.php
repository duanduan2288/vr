<?php
    if(!empty($data)):
    foreach($data as $key=>$value):
    if('folder'==$value['type']):
    ?>
    <div style="display: block;" class="tree-folder">
        <div class="tree-folder-header" data-value="<?php echo $key;?>" data-mode="hide">
            <i class="fa fa-folder-open"></i>

            <div class="tree-folder-name" >
                <?php echo $value['name'];?>
                <div class="tree-actions"></div>
            </div>
        </div>
        <div class="tree-folder-content" style="display: block;">
        </div>
    </div>
    <?php else:?>
    <div style="display: block;" class="tree-item">
        <i class="tree-dot"></i>
        <div class="tree-item-name" data-mode="unselected" data-id="<?php echo $value['name'];?>" data-value="<?php echo $key;?>">
            <i class="fa fa-bar-chart-o"></i>
            <?php echo $value['name'];?>
        </div>
    </div>
    <?php endif;?>
<?php endforeach;else:?>
        <div>暂无文件</div>
<?php endif;?>

