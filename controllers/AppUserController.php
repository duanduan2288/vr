<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/30
 * Time: 15:36
 */

namespace app\controllers;


use app\models\Suggest;
use yii\data\Pagination;
use yii\web\Controller;
use Yii;

class AppUserController extends Controller
{
	/**
	 * 用户反馈列表
	 */
	public function actionSuggest(){

		$suggest = Suggest::find()->where("1=1");
		$start_date = Yii::$app->request->get('start_date',date('Y-m-01'));
		$end_date = Yii::$app->request->get('end_date',date('Y-m-d'));
		if(!empty($start_date)){
			$suggest->andWhere("createTime >= '{$start_date} 00:00:00'");
		}
		if(!empty($end_date)){
			$suggest->andWhere("createTime <= '{$end_date} 23:59:59'");
		}
		$suggest->orderBy("id desc");
		$count = $suggest->count();
		$pages = new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
		$suggest->offset($pages->offset)->limit($pages->limit);
		$list = $suggest->all();
		return $this->render('suggest',['list'=>$list]);
	}
}