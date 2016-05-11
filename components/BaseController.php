<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/11
 * Time: 15:07
 */

namespace app\components;


use yii\web\Controller;
use app\models\User;
use Yii;
use yii\helpers\Url;
use app\models\AuthFunction;
use yii\helpers\HtmlPurifier;

class BaseController extends Controller
{
	public $menuId = 0;
	public $_outcontroller = array(
		'site',
		'menu',
		'audit-service',
		'audit-status',
		'upload'
	);

	public function init()
	{
		$this->menuId = Yii::$app->request->get ( 'menuid' );
	}

	public function beforeAction($action)
	{
		//return true;
		$this->userStatus($action);
		return $this->checkfunction($action);
	}


	/**
	 * 错误消息提示
	 * @param unknown $msg
	 * @param string $url
	 * @param number $timeout
	 */
	public function error($msg, $url = '', $timeout = 3, $script = '')
	{
		$this->pushMsg('err', $msg, $url, $timeout);
	}

	/**
	 * 正确消息提示
	 * @param unknown $msg
	 * @param string $url
	 * @param number $timeout
	 */
	public function success($msg, $url = '', $timeout = 3, $script = '')
	{
		$this->pushMsg('ok', $msg, $url, $timeout);
	}

	/**
	 * 普通消息提示
	 * @param unknown $msg
	 * @param string $url
	 * @param number $timeout
	 */
	public function info($msg, $url = '', $timeout = 3, $script = '')
	{
		$this->pushMsg('info', $msg, $url, $timeout, $script);
	}

	/**
	 * 提示消息并重定向
	 * @param  string $type 消息类型, 成功success,错误error,信息info,
	 * @param  string $message 显示的消息
	 * @param  string $url     跳转的URL
	 * @param  integer $delay   跳转间隔
	 * @param  string $script  要执行的JS脚本
	 */
	public function redirectMessage($type='info', $message, $url='', $delay=3, $script='')
	{
		if(empty($url)){
			$url = HtmlPurifier::process(Yii::$app->request->Referrer);
		}

		return $this->render('//site/error', array(
			'type'=>$type,
			'message'=>$message,
			'url'=>$url,
			'delay'=>$delay,
			'script'=>''
		));
		exit;
	}

	/**
	 * 统一消息提示
	 * @param unknown $type
	 * @param unknown $msg
	 * @param string $url
	 * @param number $timeout
	 */
	public function pushMsg($type, $msg, $url = '', $timeout = 3, $script = '')
	{
		$this->layout = "/main";
		if (yii::$app->request->isAjax) {
			$return = array(
				'info' => $type,
				'data' => (array) $msg
			);
			echo json_encode($return);
			Yii::$app->end();
		}

		if(empty($url)){
			$url = HtmlPurifier::process(Yii::$app->request->Referrer);
		}
		echo $this->render('//site/error',[
			'type'=>$type,
			'message'=>$msg,
			'url'=>$url,
			'delay'=>$timeout,
			'script'=>$script
		]);
		exit;
	}

	private function userStatus($action)
	{
		$isGuest = Yii::$app->user->isGuest;
		if(!$isGuest) {
			$uid = Yii::$app->user->id;
			$userModel = User::findOne($uid);
			$error = false;

			if(!empty($userModel)){
				$error = true;
			}
			//if($error && in_array($action->id, array('login','logout')) == false){
			//	return $this->redirect('/site/logout');
			//	Yii::$app->end();
			//}
		}
		return true;
	}

	private function checkfunction($action)
	{
		$controller_id = $this->getUniqueId();
		$action_id = $action->id;
		if (!in_array($controller_id, $this->_outcontroller)) {
			$uid = Yii::$app->user->id;
			if(empty($uid)) return $this->redirect('/site/login');
			$function = AuthFunction::findOne(['controller' => $controller_id, 'action' => $action_id]);
			if (empty($function)) {
				$this->error('你访问的控制器方法不存在,请联系管理员添加此action');
				Yii::$app->end();
			}
			$sql = "SELECT mf.function_id FROM {{%auth_user_role}} uf
						INNER JOIN  {{%auth_role}} f ON f.id = uf.role_id
						INNER JOIN  {{%auth_role_menu}} rm ON f.id = rm.role_id
						INNER JOIN  {{%auth_menu_function}} mf ON rm.menu_id = mf.menu_id
						WHERE uf.user_id = {$uid}";
			$db = Yii::$app->db->createCommand ( $sql );
			$result = $db->queryColumn ();
			if (!empty($result)) {
				if (in_array($function->id, $result)) {
					return true;
				}
			}
			$this->error("你无权查看此控制器方法： {$controller_id}/{$action_id}");
			Yii::$app->end();
		}
		return true;
	}

}