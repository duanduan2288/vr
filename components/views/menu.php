        <?php
            use app\models\AuthMenu;
            use app\models\AuthFunction;
            use \yii\db\Query;
            foreach($menu as $one):
            ?>
            <?php
                $child = AuthMenu::findOne(["parent_id" => $one['id']]);
             ?>
            <?php if (!empty($child)): ?>
                <?php
                    $i = 0;
                    $rel = "hide";
                    if(!empty($current)&&$current['id']==$one['id']){
                        $rel = "show";
                    }
                    $uid = Yii::$app->user->id;
                    $query = new Query;
                    $children =$query
                        ->select('t.*')
                        ->from('{{%auth_menu}} as t')
                        ->where("a.user_id = {$uid} AND t.parent_id={$one['id']} AND t.deleted='否'")
                        ->distinct(true)
                        ->innerJoin('{{%auth_role_menu}} r','r.menu_id=t.id')
                        ->innerJoin('{{%auth_user_role}} a','r.role_id=a.role_id')
                        ->orderBy('t.weight asc')
                        ->createCommand()->queryAll();
                 ?>
                <li class="<?php echo "show"==$rel?'active':'';?>">
                    <a href="javascript:;">
                        <i class="icon icon-<?php echo !empty($one['image'])?$one['image']:'cogs'; ?>"></i>
                        <span class="title"><?php echo $one['name']?></span>
                        <span class="selected"></span>
                        <span class="arrow <?php echo "show"==$rel?'open':'';?>"></span>
                    </a>
                    <ul class="sub-menu">
                        <?php foreach($children as $item):

                            $result = parse_url($item['link']);
                            if (isset($result['scheme']) && isset($result['host'])) {
                            } else {
                                $item['link'] =  $item['link'];
                            }
                            $i++;
                        ?>
                            <li class="<?php echo "show"==$rel&&$i==$menuId?"active":'';?>">
                                <a href="<?php echo trim($item['link']);?>?menuid=<?php echo $i?>" target="_<?php echo $item['target']?>">
                                <?php echo $item['name'];?></a>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </li>
                <?php else : ?>
                    <?php
                        $query = new Query;
                        $funs =$query
                            ->select('function_id')
                            ->from('{{%auth_menu_function}}')
                            ->where("menu_id = {$one['id']}")
                            ->createCommand()->queryColumn();
                        $controller = Yii::$app->controller->id;
                        $action = Yii::$app->controller->action->id;
                        $function = AuthFunction::findOne(["controller" => $controller, "action" => $action]);
                    ?>
                    <li class="<?php echo !empty($function)&&in_array($function->id, $funs)?'active':'';?>">
                        <a href="<?php echo trim($one['link']);?>">
                            <i class="icon icon-<?php echo !empty($one['image'])?$one['image']:'cogs'; ?>"></i>
                            <span class="title"><?php echo $one['name']?></span>
                        </a>
                    </li>
                <?php endif ?>
        <?php endforeach;?>
