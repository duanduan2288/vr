<?php

class CWMenu extends CWidget
{
	public $menuId = 0;
	public function run()
	{
		if(Yii::app()->user->isGuest)
			return false;
		$menu = $this->_getMenuList();
		$current = $this->_getCurrentMenu();
		$this->render('menu',array('menu'=>$menu,'current'=>$current,'menuId'=>$this->menuId));
	}

	protected function _getMenuList()
	{
	    $criteria = new CDbCriteria();
	    $criteria->compare('a.user_id', Yii::app()->user->id);
	    $criteria->compare('t.parent_id', '0');
	    $criteria->compare('t.platform', '注册商');
	    $criteria->compare('t.deleted', '否');
	    // $criteria->compare('t.deleted', '0');
	    $criteria->order = 't.`weight` asc';
	    $criteria->distinct = true;//是否唯一查询
	    $criteria->join = '
					INNER JOIN auth_role_menu r on(r.menu_id=t.id)
					INNER JOIN auth_user_role a on(r.role_id=a.role_id)
			';
	     return AuthMenu::model()->findAll($criteria);
	}

	protected function _getCurrentMenu()
	{
			$controller = is_object($this->controller)?$this->controller->getId ():'';
			$action = is_object($this->controller->action)?$this->controller->action->getId ():'';
			$user_id = Yii::app()->user->id;
			$sql = "SELECT m1.id FROM auth_menu m1
						INNER JOIN  auth_menu m2 ON m1.id = m2.parent_id
						INNER JOIN  auth_menu_function amf ON m2.id = amf.menu_id
						INNER JOIN  auth_function f ON amf.function_id = f.id AND f.action='{$action}' AND f.controller='{$controller}' AND f.platform = '注册商'
						WHERE m1.parent_id = 0 AND m1.platform = '注册商' AND m1.deleted = '否'";
			$db = Yii::app ()->db->createCommand ( $sql );
			$result = $db->queryAll ();
			// AND m2.link ='/{$controller}/{$action}'
			if(!empty($result)){
					return $result[0];
			}else{
					return array();
			}
	}

}

