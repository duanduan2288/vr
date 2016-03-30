<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015-5-20
 * Time: 15:49
 */
    namespace app\components;

    use Yii;
    use yii\base\Widget;
    use \yii\db\Query;
    use app\models\AuthMenu;

    class MenuWidget extends Widget
    {
        public $menuId;

        public function init()
        {
            parent::init();
            if ($this->menuId === null) {
                $this->menuId = 0;
            }
        }

        public function run()
        {
            if(\Yii::$app->user->isGuest)
                return false;
            $menu = $this->_getMenuList();
            $current = $this->_getCurrentMenu();
            return $this->render('menu',array('menu'=>$menu,'current'=>$current,'menuId'=>$this->menuId));
        }

        protected function _getMenuList()
        {
            $user_id = Yii::$app->user->id;
            $query = new Query;
            $query->select('t.*')
                ->from('{{%auth_menu}} as t')
                ->where("a.user_id={$user_id} AND t.parent_id=0 AND t.deleted='否'")
                ->innerJoin('{{%auth_role_menu}} r','r.menu_id=t.id')
                ->innerJoin('{{%auth_user_role}} a','r.role_id=a.role_id')
                ->orderBy('t.weight asc');
            $command = $query->createCommand();
            $sql = $command->sql;
            $rows = $command->queryAll();
            return $rows;
        }

        protected function _getCurrentMenu()
        {
            $controller = Yii::$app->controller->id;
            $action = Yii::$app->controller->action->id;
            $user_id = Yii::$app->user->id;
            $sql = "SELECT m1.id FROM {{%auth_menu}} m1
						INNER JOIN  {{%auth_menu}} m2 ON m1.id = m2.parent_id
						INNER JOIN  {{%auth_menu_function}} ON m2.id = {{%auth_menu_function}}.menu_id
						INNER JOIN  {{%auth_function}} ON {{%auth_menu_function}}.function_id = {{%auth_function}}.id AND action='{$action}' AND controller='{$controller}'
						WHERE m1.parent_id = 0 AND m1.deleted = '否'";
            $db = Yii::$app->db->createCommand ( $sql );
            $result = $db->queryAll ();
            if(!empty($result)){
                return $result[0];
            }else{
                return array();
            }
        }

    }