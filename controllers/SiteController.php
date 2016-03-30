<?php
    namespace app\controllers;

    use app\models\User;
    use Yii;
    use yii\filters\AccessControl;
    use yii\web\Controller;
    use yii\filters\VerbFilter;
    use app\models\RegistryUser;
    use app\models\Service;
    use yii\web\Cookie;
    use yii\db\Expression;
    use app\components\Util;
    use app\models\ServiceOperationLog;

    /**
     * Site controller
     */
    class SiteController extends Controller
    {
        public $enableCsrfValidation = false;
        /**
         * @inheritdoc
         */
        public function actions()
        {
            return [
                'error' => [
                    'class' => 'yii\web\ErrorAction',
                ],
                'captcha' => [
                    'class' => 'yii\captcha\CaptchaAction',
                    'maxLength' => 5,
                    'minLength' => 5
                ],
            ];
        }

        public function actionIndex()
        {
            if (\Yii::$app->user->isGuest) {
                return $this->redirect('/site/login');
            }
            return $this->render('index');
        }

        /**
         * 登陆页、提交登
         */
        public function actionLogin()
        {
            $this->layout = 'login';
            if (!\Yii::$app->user->isGuest) {
                return $this->goHome();
            }
            $cookie = Yii::$app->request->getCookies();
            $email 	= isset($cookie['username']->value) ? $cookie['username']->value : '';
            return $this->render('login', array('email'=>$email));
        }
        /**
         * 登陆验证
         */
        public function actionChecklogin()
        {
            $this->layout = 'login';

            $username   = Yii::$app->request->post('username', '');
            $password   = Yii::$app->request->post('password', '');
            $verifyCode = Yii::$app->request->post('verifyCode', '');
            $remember   = Yii::$app->request->post('remember', 0);

            $username   = trim(strtolower($username));
            $password   = trim($password);
            $verifyCode = trim($verifyCode);

            if (empty($username)) {
                Yii::$app->getSession()->setFlash('login', '请输入登陆邮箱');
                goto render;
            }
            if (empty($password)) {
                Yii::$app->getSession()->setFlash('login', '请输入登陆密码');
                goto render;
            }
            $ip = Yii::$app->getRequest()->getUserIP();
            $curr_code  = $this->createAction('captcha')->getVerifyCode();
            if (strtolower($verifyCode)!==strtolower($curr_code)) {
                Yii::$app->getSession()->setFlash('login', '验证码不正确');
                goto render;
            }

            $time = date('Y-m-d H:i:s');
            $model = new User();
            $user = $model->findOne(array('logonName'=>$username));
            if ($user === null) {
                $log = 'IP:'.$ip.' error-loginname：'.$username.' password：'.$password.' datetime：'.$time;
                Yii::info($log, __METHOD__);
                Yii::$app->getSession()->setFlash('login', '用户名错误');
                goto render;
            }
            if ($user->logonPwd !== Service::create_password($password)) {
                $session['_check_verifycode'] = 1;
                Yii::error('IP:'.$ip.' loginname：'.$username.' error-password：'.$password.' datetime：'.$time,'login_error');
                Yii::$app->getSession()->setFlash('login', '密码错误');
                goto render;
            }
            if(Yii::$app->getUser()->login($user)){
                return  $this->redirect('/site/index');
            }

            render:
            return $this->render('login');
        }
        /**
         * 密码必须包含大写字母，小写字母和数字
         */
        public function actionCheckpassword(){
            $password = yii::$app->request->post('password', '');
            $result = false;
            if(preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]*$/",$password)){
                $result = true;
            }
            echo json_encode($result);
            yii::$app->end();
        }
        /**
         * 注册验证nick_name是否存在
         */
        public function actionCheckUsername()
        {
            $nick_name = yii::$app->request->post('email', '');
            $result = true;
            $user = User::findOne(['logonName'=> $nick_name]);
            if ($user) {
                $result = false;
            }
            echo json_encode($result);
            yii::$app->end();
        }
        /**
         * 修改密码验证旧密码是否正确
         */
        public function actionCheckoldpassword()
        {
            $uid = Yii::$app->user->id;
            $old_password = yii::$app->request->post('old_password', '');
            $user_info = User::findOne(['id'=>$uid]);
            $o_password = $user_info['password'];
            $c_password = Service::create_password($old_password);
            $result = true;
            if ($o_password !== $c_password) {
                $result = false;
            }
            echo json_encode($result);
            yii::$app->end();
        }

        /**
         * 检查验证码是否正确
         */
        public function actionGetverifycode(){
            if (isset($_REQUEST['verifyCode'])){
                $verify = $this->createAction('captcha')->getVerifyCode();
                if (strtolower($_REQUEST['verifyCode']) == strtolower($verify)){
                    echo 'true';
                }else{
                    echo 'false';
                }
            }else{
                echo 'false';
            }
        }

        public function actionLogout()
        {
            Yii::$app->user->logout();

            return $this->goHome();
        }

        public function actionError(){
            $this->layout = '//layouts/main';
            if($error=Yii::$app->errorHandler->error)
            {
                return $this->render('error',['message'=>$error['message'],'url'=>'','script'=>'','delay'=>0,'type'=>'']);
            }else{
                return $this->render('error',['message'=>$error['message'],'url'=>'','script'=>'','delay'=>0,'type'=>'']);
            }

        }

        /**
         * 修改个人密码
         * @return [type] [description]
         */
        public function actionChange_password()
        {
            $uid = Yii::$app->user->id;
            if (!empty($uid)){
                $info = User::findOne($uid);

                if(isset($_POST['password']))
                {
                    // $info = RegistrarUser::model()->findByPk($_POST['id']);
                    $connection = Yii::$app->db;
                    /****密码更新****/
                    $password = trim($_POST['password']);
                    if ($password) {
                        $hash = $info->salt;
                        if(!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[\s\S]*$/",$password)){
                            $this->error('密码必须包含大写字母、小写字母、数字');
                        }
                        $password = Service::create_password($password);
                    }else{
                        $password = $info->password;
                        $hash = $info->salt;
                    }
                    $staus = $connection->createCommand()->update('vr_admin', [
                        'password' => $password,
                        'last_login_time' => new Expression('NOW()'),
                        'last_login_ip' => Util::get_ip(),
                    ], "id={$uid}")->execute();

                    if ($staus) {
                        return $this->redirect(array('/site/logout'));
                    }
                }
                return $this->render('change_password',array('info'=>$info));
            }else{
                return $this->redirect('/site/login');
            }
        }

    }
