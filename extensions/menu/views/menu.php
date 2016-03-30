        <?php foreach($menu as $one):?>
            <?php
                $child = AuthMenu::model()->find("parent_id = {$one->id}");
             ?>
            <?php if (!empty($child)): ?>
                <?php
                    $i = 0;
                    $rel = "hide";
                    if(!empty($current)&&$current['id']==$one->id){
                        $rel = "show";
                    }
                    $criteria = new CDbCriteria();
                    $criteria->compare('a.user_id', Yii::app()->user->id);
                    $criteria->compare('t.parent_id', $one->id);
                    $criteria->compare('t.platform', '注册商');
                    $criteria->compare('t.deleted', '否');
                    $criteria->order = 't.`weight` asc';
                    $criteria->distinct = true;//是否唯一查询
                    $criteria->join = '
                        LEFT OUTER JOIN auth_role_menu r on(r.menu_id=t.id)
                        LEFT OUTER JOIN auth_user_role a on(r.role_id=a.role_id)
                    ';
                    $children =  AuthMenu::model()->findAll($criteria);
                 ?>
                <li class="<?php echo "show"==$rel?'active':'';?>">
                    <a href="javascript:;">
                        <i class="icon icon-<?php echo !empty($one->image)?$one->image:'cogs'; ?>"></i>
                        <span class="title"><?php echo $one->name?></span>
                        <span class="selected"></span>
                        <span class="arrow <?php echo "show"==$rel?'open':'';?>"></span>
                    </a>
                    <ul class="sub-menu">
                        <?php foreach($children as $item):

                            $result = parse_url($item->link);
                            if (isset($result['scheme']) && isset($result['host'])) {
                            } else {
                                $item->link =  Yii::app()->baseUrl.$item->link;
                            }
                            $i++;
                        ?>
                            <li class="<?php echo "show"==$rel&&$i==$menuId?"active":'';?>">
                                <a href="<?php echo trim($item->link);?>?menuid=<?php echo $i?>" target="_<?php echo $item->target?>">
                                <?php echo $item->name?></a>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </li>
                <?php else : ?>
                    <?php
                        $funs = Yii::app()->db->createCommand()
                            ->select('function_id')
                            ->from('auth_menu_function')
                            ->where("menu_id = {$one->id}")
                            ->queryColumn();
                        $controller = is_object($this->controller)?$this->controller->getId ():'';
                        $action = is_object($this->controller->action)?$this->controller->action->getId ():'';
                        $function = AuthFunction::model()->find("platform = '注册商' and controller = '{$controller}' and action = '{$action}'");
                    ?>
                    <li class="<?php echo !empty($function)&&in_array($function->id, $funs)?'active':'';?>">
                        <a href="<?php echo trim($one->link);?>">
                            <i class="icon icon-<?php echo !empty($one->image)?$one->image:'cogs'; ?>"></i>
                            <span class="title"><?php echo $one->name?></span>
                        </a>
                    </li>
                <?php endif ?>
        <?php endforeach;?>
