<?php
namespace app\controllers;
use app\models\Notification;
use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use app\components\Util;
use app\models\Service;
use app\models\Dictionary;
use yii\db\Expression;

class NotificationController extends Controller
{
		public function actionIndex()
		{
			$uid = Yii::$app->user->id;
    		if (!empty($uid)){
				  $search_title=(isset($_GET['search_title']))?$_GET['search_title']:'';
				  $search_priority=(isset($_GET['search_priority']))?$_GET['search_priority']:'';
				  $search_status=(isset($_GET['search_status']))?$_GET['search_status']:'';
				  $data = Notification::find()->where("1=1");
				  if (!empty($search_title)) {
					  $data->andWhere("title LIKE '%{$search_title}%'");
				  }
				  if (!empty($search_priority)) {
					  $data->andWhere("priority = '{$search_priority}'");
				  }
				  if (!empty($search_status)) {
					  $data->andWhere("status = '{$search_status}'");
				  }

				$data->orderBy("id desc");
				$count = $data->count();
				$pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
				$notifications = $data->offset($pages->offset)->limit($pages->limit)->all();
				return $this->render('index',array(
					  'notifications' => $notifications,
					  'pages'=>$pages,
					  'search_title'=>$search_title,
					  'search_priority'=>$search_priority,
					  'search_status'=>$search_status
				  ));
			  }else{
        		return $this->redirect('/site/login');
      	}
		}

		public function actionMy()
		{
			$uid = Yii::$app->user->id;
    		if (!empty($uid)){

			  $search_title=(isset($_GET['search_title']))?$_GET['search_title']:'';
			  $search_priority=(isset($_GET['search_priority']))?$_GET['search_priority']:'';
			  $search_status=(isset($_GET['search_status']))?$_GET['search_status']:'';

				$data = Notification::find()->where( "receiver = ".$uid);
				if (!empty($search_title)) {
					$data->andWhere("title LIKE '%{$search_title}%'");
				}
				if (!empty($search_priority)) {
					$data->andWhere("priority = '{$search_priority}'");
				}
				if (!empty($search_status)) {
					$data->andWhere("status = '{$search_status}'");
				}
				$data->orderBy("id desc");
				$count = $data->count();
				$pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
				$notifications = $data->offset($pages->offset)->limit($pages->limit)->all();
			    return  $this->render('my',array(
			          'notifications' => $notifications,
			          'pages'=>$pages,
			          'search_title'=>$search_title,
			          'search_priority'=>$search_priority,
			          'search_status'=>$search_status
			      ));
		  	}else{
        		return $this->redirect('/site/login');
      		}
		}

		/**
		 * 发送消息
		 * @return [type] [description]
		 */
		public function actionCreate()
		{
			$agents = Yii::$app->db->createCommand()
						    ->select('id,company_name')
							->from('agent')
							->where("status = '审核通过' and deleted = '否'")
							->queryAll();
			return $this->render('create',array('agents'=>$agents));
		}

		/**
		 * 保存消息
		 * @return [type] [description]
		 */
		public function actionSave()
		{
				if(!Yii::$app->user->isGuest) {
						$company_id = trim($_POST['notification']['registrar']);
            			$role_id = trim($_POST['notification']['registrar_role']);
						$users = isset($_POST['notification']['registrar_user'])?$_POST['notification']['registrar_user']:array();
						$priority = trim($_POST['notification']['priority']);
						$title = htmlspecialchars(trim($_POST['notification']['title']));
						$content = htmlspecialchars(trim($_POST['notification']['content']));

						if (!isset($_POST['notification']['registrar_user'])) {
								$users = Yii::$app->db->createCommand()
											    ->select('id')
													->from('agent_user')
							            ->where("agent_id = {$company_id} and status = '正常' and deleted = '否'")
													->queryColumn();
								if ($role_id != 'all') {
										$ids = Yii::$app->db->createCommand()
										    ->select('user_id')
												->from('auth_user_role')
						            ->where("role_id = {$role_id}")
												->queryColumn();
										$users = array_intersect($users, $ids);
								}
						}

						if (!empty($users)) {
								foreach ($users as $k => $v)
								{
										$user = AgentUser::model()->findByPk($v);
										if (empty($user)) continue;
										$model = new Notification;
										$model->title = $title;
				            $model->content = $content;
				            $model->issue_id = 0;
				            $model->creator = Yii::$app->user->id;
				            $model->receiver = $v;
				            $model->priority = $priority;
				            $model->created =  new CDbExpression('NOW()');
				            $model->save();
								}
								$this->redirect(array('/notification/index'));
								// echo json_encode(array('info'=>'ok','data'=>array(),'msg'=>'发送成功'));
						}
						$this->redirect(array('/notification/create'));
						// echo json_encode(array('info'=>'error','data'=>array(),'msg'=>'无用户可以发送'));
        }else{
        	$this->redirect(array('/site/login'));
        	// echo json_encode(array('info'=>'error','data'=>array(),'msg'=>'请先登录'));
        }
		}

		public function actionDelete($id)
	  {
	      $info = $this->loadModel($id);
	  		$connection = Yii::$app->db;
	      $status = $connection->createCommand()->delete("notification", "id={$id}")->execute();
	      if ($status) {
	          ServiceOperationLog::create_operation_log('1028','',json_encode($info->attributes),'删除消息','/notification/delete');
	          $this->redirect(array('/notification/index'));
	      }
	  }

	  public function actionShow()
	  {
	      $uid = Yii::$app->user->id;
	      if (!empty($uid)){
	          $id = Yii::$app->request->get('id', '');
	          $info = $this->loadModel($id);
	          if (empty($info)) {
	              $this->error('消息不存在');
	              Yii::$app->end();
	          }
	        	if ($info['status'] == '未读' && $uid == $info['receiver']) {
	          		Yii::$app->db->createCommand()->update('notification', [
	            				'status' => '已读',
	            				'read_ip' => Util::get_ip(),
	            				'read' => new Expression('NOW()')
	            			], "id={$id}")->execute();
	          		//$num = Service::get_unread_notification($uid);
	          }
	          if (isset($info['issue_id'])&&$info['issue_id']) {
	          		$issue_info = Issue::findOne($info['issue_id']);
								if (!empty($issue_info)&&isset($issue_info['type'])&&isset($issue_info['guid'])) {
										$href = Dictionary::$issueTypeMaping[$issue_info['type']];
										return $this->redirect(array($href,'id'=>$issue_info['guid']));
										Yii::$app->end();
								}
	          }
	          $info['content'] = Service::fix_blog_HTML($info['content']);
	          return $this->render('show',array(
	              'info' => $info,
	          ));
	      }else{
	          return $this->redirect('/site/login');
	      }
	  }

		/**
		 * 获取系统中的注册商的所有角色
		 * @return [type] [description]
		 */
		public function actionGetroles()
		{
				$data = array();
				$roles = Yii::$app->db->createCommand()
								->select('id,name')
								->from('auth_role')
                ->where("platform = '代理商' and agent_id = 0")
								->queryAll();
								// print_r($roles);die;
				echo json_encode(array('info'=>'ok','data'=>$roles));
		}

		/**
		 * 获取注册商的全部用户或者某一角色下的所有用户
		 * @return [type] [description]
		 */
		public function actionGetusers()
		{
				$data = array();
				$company = Yii::$app->request->getPost('company', '');
				$role = Yii::$app->request->getPost('role', '');
				$all_ids = Yii::$app->db->createCommand()
						    ->select('id')
								->from('agent_user')
		            ->where("agent_id = {$company[0]} and status = '正常' and deleted = '否'")
								->queryColumn();
				if ($role[0] != 'all') {
						$ids = Yii::$app->db->createCommand()
						    ->select('user_id')
								->from('auth_user_role')
		            ->where("role_id = {$role[0]}")
								->queryColumn();
						$all_ids = array_intersect($all_ids, $ids);
				}
				if (!empty($all_ids)) {
						$data = Yii::$app->db->createCommand()
						    ->select('id,email,first_name,last_name')
								->from('agent_user')
		            ->where("id in (".implode(',', $all_ids).")")
								->queryAll();
				}
				echo json_encode(array('info'=>'ok','data'=>$data));
		}

		public function getuser($uid)
		{
				$class = $uid < 100000 ? 'RegistrarUser' : 'AgentUser';
				$info = $class::model()->findByPk($uid);
				if (!empty($info) && isset($info['email'])) {
						return  $info['email'];
				}
				return '';
		}

		public function gettitle($n_id)
		{
				$n_info = Notification::model()->findByPk($n_id);
				$href = '/notification/show/id/'.$n_id;
				$issue_info = Issue::model()->findByPk($n_info['issue_id']);
				if (!empty($issue_info)&&isset($issue_info['type'])&&isset($issue_info['guid'])) {
						$href = Dictionary::$issueTypeMaping[$issue_info['type']];
						$href .= '?id='.$issue_info['guid'];
				}
				$str = '<a href="'.$href.'">'.$n_info['title'].'</a>';
				return $str;
		}

		/**
     * 获取未读消息
     */
    public static function actionUpdate()
    {
        $uid = Yii::$app->user->id;
        if (!empty($uid)) {
        		$num = Service::get_unread_notification($uid);
            // $num = Notification::model()->count("receiver = {$uid} and status = '未读'");
            // $cookie = new CHttpCookie('notification', $num);
		        // $cookie->expire = time()+600;  //有效期10分钟
		        // Yii::$app->request->cookies['notification']=$cookie;
            echo json_encode(array('info'=>'ok','msg'=>'','data'=>array('num'=>$num)));
        }else{
            echo json_encode(array('info'=>'error','msg'=>'请先登录','data'=>array()));
        }
    }

		public function loadModel($id)
	  {
	      $model = Notification::findOne($id);
	      // if($model===null)
	          // throw new CHttpException(404,'The requested page does not exist.');
	      return $model;
	  }

}