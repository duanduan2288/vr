<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\web;

use app\models\User;
use Yii;
use yii\base\InlineAction;
use yii\helpers\Url;
use app\models\AuthFunction;
use yii\helpers\HtmlPurifier;
use app\models\RegistryUser;

/**
 * Controller is the base class of web controllers.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Controller extends \yii\base\Controller
{
    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = false;
    /**
     * @var array the parameters bound to the current action.
     */
    public $actionParams = [];
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
        return true;
        //$this->userStatus($action);
        //return $this->checkfunction($action);
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

            if($error && in_array($action->id, array('login','logout')) == false){
               return $this->redirect('/site/logout');
                Yii::$app->end();
            }
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
            $sql = "SELECT mf.function_id FROM auth_user_role uf
						INNER JOIN  auth_role f ON f.id = uf.role_id
						INNER JOIN  auth_role_menu rm ON f.id = rm.role_id
						INNER JOIN  auth_menu_function mf ON rm.menu_id = mf.menu_id
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

    /**
     * Renders a view in response to an AJAX request.
     *
     * This method is similar to [[renderPartial()]] except that it will inject into
     * the rendering result with JS/CSS scripts and files which are registered with the view.
     * For this reason, you should use this method instead of [[renderPartial()]] to render
     * a view to respond to an AJAX request.
     *
     * @param string $view the view name. Please refer to [[render()]] on how to specify a view name.
     * @param array $params the parameters (name-value pairs) that should be made available in the view.
     * @return string the rendering result.
     */
    public function renderAjax($view, $params = [])
    {
        return $this->getView()->renderAjax($view, $params, $this);
    }

    /**
     * Binds the parameters to the action.
     * This method is invoked by [[\yii\base\Action]] when it begins to run with the given parameters.
     * This method will check the parameter names that the action requires and return
     * the provided parameters according to the requirement. If there is any missing parameter,
     * an exception will be thrown.
     * @param \yii\base\Action $action the action to be bound with parameters
     * @param array $params the parameters to be bound to the action
     * @return array the valid parameters that the action can run with.
     * @throws BadRequestHttpException if there are missing or invalid parameters.
     */
    public function bindActionParams($action, $params)
    {
        if ($action instanceof InlineAction) {
            $method = new \ReflectionMethod($this, $action->actionMethod);
        } else {
            $method = new \ReflectionMethod($action, 'run');
        }

        $args = [];
        $missing = [];
        $actionParams = [];
        foreach ($method->getParameters() as $param) {
            $name = $param->getName();
            if (array_key_exists($name, $params)) {
                if ($param->isArray()) {
                    $args[] = $actionParams[$name] = (array) $params[$name];
                } elseif (!is_array($params[$name])) {
                    $args[] = $actionParams[$name] = $params[$name];
                } else {
                    throw new BadRequestHttpException(Yii::t('yii', 'Invalid data received for parameter "{param}".', [
                        'param' => $name,
                    ]));
                }
                unset($params[$name]);
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $actionParams[$name] = $param->getDefaultValue();
            } else {
                $missing[] = $name;
            }
        }

        if (!empty($missing)) {
            throw new BadRequestHttpException(Yii::t('yii', 'Missing required parameters: {params}', [
                'params' => implode(', ', $missing),
            ]));
        }

        $this->actionParams = $actionParams;

        return $args;
    }

    /**
     * @inheritdoc
     */
    //public function beforeAction($action)
    //{
    //    if (parent::beforeAction($action)) {
    //        if ($this->enableCsrfValidation && Yii::$app->getErrorHandler()->exception === null && !Yii::$app->getRequest()->validateCsrfToken()) {
    //            throw new BadRequestHttpException(Yii::t('yii', 'Unable to verify your data submission.'));
    //        }
    //        return true;
    //    }
    //
    //    return false;
    //}

    /**
     * Redirects the browser to the specified URL.
     * This method is a shortcut to [[Response::redirect()]].
     *
     * You can use it in an action by returning the [[Response]] directly:
     *
     * ```php
     * // stop executing this action and redirect to login page
     * return $this->redirect(['login']);
     * ```
     *
     * @param string|array $url the URL to be redirected to. This can be in one of the following formats:
     *
     * - a string representing a URL (e.g. "http://example.com")
     * - a string representing a URL alias (e.g. "@example.com")
     * - an array in the format of `[$route, ...name-value pairs...]` (e.g. `['site/index', 'ref' => 1]`)
     *   [[Url::to()]] will be used to convert the array into a URL.
     *
     * Any relative URL will be converted into an absolute one by prepending it with the host info
     * of the current request.
     *
     * @param integer $statusCode the HTTP status code. Defaults to 302.
     * See <http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html>
     * for details about HTTP status code
     * @return Response the current response object
     */
    public function redirect($url, $statusCode = 302)
    {
        return Yii::$app->getResponse()->redirect(Url::to($url), $statusCode);
    }

    /**
     * Redirects the browser to the home page.
     *
     * You can use this method in an action by returning the [[Response]] directly:
     *
     * ```php
     * // stop executing this action and redirect to home page
     * return $this->goHome();
     * ```
     *
     * @return Response the current response object
     */
    public function goHome()
    {
        return Yii::$app->getResponse()->redirect(Yii::$app->getHomeUrl());
    }

    /**
     * Redirects the browser to the last visited page.
     *
     * You can use this method in an action by returning the [[Response]] directly:
     *
     * ```php
     * // stop executing this action and redirect to last visited page
     * return $this->goBack();
     * ```
     *
     * For this function to work you have to [[User::setReturnUrl()|set the return URL]] in appropriate places before.
     *
     * @param string|array $defaultUrl the default return URL in case it was not set previously.
     * If this is null and the return URL was not set previously, [[Application::homeUrl]] will be redirected to.
     * Please refer to [[User::setReturnUrl()]] on accepted format of the URL.
     * @return Response the current response object
     * @see User::getReturnUrl()
     */
    public function goBack($defaultUrl = null)
    {
        return Yii::$app->getResponse()->redirect(Yii::$app->getUser()->getReturnUrl($defaultUrl));
    }

    /**
     * Refreshes the current page.
     * This method is a shortcut to [[Response::refresh()]].
     *
     * You can use it in an action by returning the [[Response]] directly:
     *
     * ```php
     * // stop executing this action and refresh the current page
     * return $this->refresh();
     * ```
     *
     * @param string $anchor the anchor that should be appended to the redirection URL.
     * Defaults to empty. Make sure the anchor starts with '#' if you want to specify it.
     * @return Response the response object itself
     */
    public function refresh($anchor = '')
    {
        return Yii::$app->getResponse()->redirect(Yii::$app->getRequest()->getUrl() . $anchor);
    }
}
