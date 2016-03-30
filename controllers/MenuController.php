<?php
namespace app\controllers;
use yii;
use yii\web\Controller;
use \yii\db\Query;
use app\models\AuthMenu;
use yii\data\Pagination;
use yii\db\Expression;
use app\models\AuthMenuFunction;
use yii\db\Exception;
use app\models\ServiceOperationLog;

class MenuController extends Controller
{
    public function actionIndex()
    {
        $uid = Yii::$app->user->id;
        if(!empty($uid)){
            // 所有的顶级菜单
             $query = new Query();
             $query->select('id,name,platform')
                            ->from('vr_auth_menu')
                            ->where('parent_id = 0 and deleted = "否"')
                            ->orderBy('weight asc');
            $parent_menus = $query->createCommand()->queryAll();

            $search_name=(isset($_GET['search_name']))?$_GET['search_name']:'';
            $search_parent_id=(isset($_GET['search_parent_id']))?$_GET['search_parent_id']:'';

            $sort=(isset($_GET['sort']))?$_GET['sort']:'';
            $order=(isset($_GET['order']))?$_GET['order']:'';

            $querylist = new Query();
            $querylist->select('*')->from('vr_auth_menu');
            $querylist->where("1=1");
            if (!empty($search_name))
            {
                $querylist->andWhere("name LIKE '%{$search_name}%'");
            }
            if (!empty($search_parent_id))
            {
                $querylist->andWhere("parent_id = {$search_parent_id}");
            }

            $o = "";
            if (!empty($sort)) {
                $o = $sort;
            } else {
                $o = "id";
            }

            if (!empty($order) && ($order == 'asc')) {
                $o .= " asc";
            } else {
                $o .= " desc";
            }

            if($order == 'asc') {
                $weight_sort= '/menu/index?sort=weight&order=desc';
                $parent_id_sort= '/menu/index?sort=parent_id&order=desc';
                $name_sort= '/menu/index?sort=name&order=desc';
            } else {
                $weight_sort= '/menu/index?sort=weight&order=asc';
                $parent_id_sort= '/menu/index?sort=parent_id&order=asc';
                $name_sort= '/menu/index?sort=name&order=asc';
            }
            $querylist->orderBy($o);

            $count = $querylist->count();
            $pages = new Pagination(['defaultPageSize'=>1000,'totalCount'=>$count]);
            $querylist->offset($pages->offset)->limit($pages->limit);
            $menus = $querylist->all();

            return $this->render('index',array(
                'parent_menus' =>$parent_menus,
                'menus' => $menus,
                'pages'=>$pages,
                'search_name'=>$search_name,
                'sort'=>$sort,
                'order'=>$order,
                'weight_sort'=>$weight_sort,
                'parent_id_sort'=>$parent_id_sort,
                'name_sort'=>$name_sort
            ));
        }else{
           return  $this->redirect('/site/login');
        }
    }

    public function actionCreate()
    {
        $uid = Yii::$app->user->id;
        if(!empty($uid)){
            $id = '';
            $menu_functions = array();
            if(isset($_REQUEST['id'])&&!empty($_REQUEST['id']))
            {
                $id = intval($_REQUEST['id']);
                $model = $this->loadModel($id);
                $query = new Query();
                $query->select('function_id')
                        ->from('auth_menu_function')
                        ->where("menu_id = {$id}")
                        ->orderBy('id desc');
                $menu_functions = $query->createCommand()->queryColumn();
            }
            if(empty($model))
            {
                $model = new AuthMenu;
            }
            $platform=(isset($_GET['type'])&&$_GET['type']=='2')?'注册商':'注册局';
            $type=(isset($_GET['type'])&&$_GET['type']=='2')?'2':'1';
            // 所有的顶级菜单
            $query = new Query();
            $query->select('id,name,platform')
                ->from('auth_menu')
                ->where('parent_id = 0 and platform = "'.$platform.'" and deleted = "否"')
                ->orderBy('weight asc');
            $parent_menus = $query->createCommand()->queryAll();

    		// all function
            $queryf = new Query();
            $queryf->select('id,controller,action,platform')
                ->from('auth_function')
                ->where('platform = "'.$platform.'"')
                ->orderBy('id desc');
            $functions = $queryf->createCommand()->queryAll();

            if(isset($_POST['Menu']))
            {

                $before_edit = $id != '' ? json_encode($model->attributes) : '';
                $type_id = '1009';
                $type_s = '添加菜单';
                $model->created = new Expression('NOW()');
                $model->modified = new Expression('NOW()');
                $model->default_menu= 0 ;
                $model->attributes = $_POST['Menu'];

                if($id)
                {
                    $type_id = '1010';
                    $type_s = '修改菜单';
                }
                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
    			try {

                    $model->save();

                    if ($id != '')
                    {
                        $connection->createCommand()->delete("auth_menu_function", "menu_id={$id}")->execute();
                    }
    			    if (!empty($_POST['function']))
                    {
    			    	foreach ($_POST['function'] as $key => $value)
                        {
    		    			$amf = new AuthMenuFunction();
    			    		$amf->menu_id = $model->id;
    			    		$amf->function_id = $value;
    			    		$amf->created = new Expression('NOW()');
    			    		$amf->modified = new Expression('NOW()');
    			    		$amf->save();
    			    	}
    			    }
                    //记录操作日志
                    ServiceOperationLog::create_operation_log(json_encode($model->attributes),$before_edit,$type_s,
                        '/menu/create',$uid);

    			    $transaction->commit();
                } catch(Exception $e) {

    				$transaction->rollBack();
    			}
                return $this->redirect(array('/menu/index'));
            }

            return $this->render('create',array(
                'model'=>$model,
                'parent_menus'=>$parent_menus,
                'functions'=>$functions,
                'menu_functions'=>$menu_functions,
                'platform'=>$platform,
                'type'=>$type
                ));
        }else{
           return $this->redirect('/site/login');
        }
    }

    public function actionDelete($id)
    {
        $uid = Yii::$app->user->id;
        if(!empty($uid)){
            $type=(isset($_GET['type'])&&$_GET['type']=='2')?'2':'1';
          	$connection = Yii::$app->db;
          	$transaction = $connection->beginTransaction();
    		try {
                $info = $this->loadModel($id);
                $s = $info['deleted']=='是'?'否':'是';
                $connection->createCommand()->update('auth_menu', [
                            'deleted' => $s,
                        ], "id={$id}")->execute();

                //记录操作日志
                ServiceOperationLog::create_operation_log('',json_encode($info->attributes),'删除菜单','/menu/delete',$uid);
    			$transaction->commit();
    		} catch(Exception $e) {
    			$transaction->rollBack();
    		}
           return $this->redirect(array('/menu/index'));
        }else{
            return $this->redirect('/site/login');
        }
    }

    public function loadModel($id)
    {
        $model = AuthMenu::findOne($id);
        if($model===null)
            throw new Exception(404,'The requested page does not exist.');
        return $model;
    }
}
