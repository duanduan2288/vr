<?php
    /**
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2014-11-27
     * Time: 11:47
     */
namespace app\controllers;
use app\components\BaseController;
use yii;
use yii\web\Controller;
use yii\db\Expression;
use app\components\Util;
use app\models\Service;
use app\models\UploadFile;

    class UploadController extends BaseController{

        /**
         * 上传
         * @author xiaozhao
         * @since 2012-11-9
         */
        public function actionUploadFile()
        {
            $type 		= isset($_POST['type']) ? $_POST['type'] : 'wav,zip,rar,doc,docx,xls,csv,xlsx,png,jpg,jpeg,gif,bmp,pdf';
            $size 		= isset($_POST['size']) ? $_POST['size'] : 20;
            $fileTypes 	= explode(',', $type);
            $minsize    = isset($_POST['minsize']) ? $_POST['minsize']:0;
            if($minsize>0){
                $size = 2;
            }

            $user_id 	= Yii::$app->user->id;
            $company_id = 0;
            $fileElementName = isset($_POST['file_name']) ? $_POST['file_name'] : 'fileupload';

            $error = "";
            if(!empty($_FILES[$fileElementName]['error']))
            {
                switch($_FILES[$fileElementName]['error'])
                {
                    case '1':
                        $error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
                        break;
                    case '2':
                        $error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
                        break;
                    case '3':
                        $error = 'The uploaded file was only partially uploaded';
                        break;
                    case '4':
                        $error = '没有选择上传文件';
                        break;
                    case '6':
                        $error = 'Missing a temporary folder';
                        break;
                    case '7':
                        $error = 'Failed to write file to disk';
                        break;
                    case '8':
                        $error = 'File upload stopped by extension';
                        break;
                    default:
                        $error = 'No error code avaiable';
                }
            }elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
            {
                $error = '没有选择上传文件';
            }else
            {

                if (!empty($_FILES)) {
                    $fileParts = pathinfo($_FILES[$fileElementName]['name']);
                    if (isset($fileParts['extension']) && in_array(strtolower($fileParts['extension']),$fileTypes)) {

                        $tempSize = $_FILES[$fileElementName]['size'];
                        if ($tempSize < 1024*1024*$size && $tempSize > 1024*$minsize)
                        {
                            $upload_dir = Yii::$app->params['upload']['attachment_root_dir'];
                            //$upload_dir 		= str_replace('frontend', 'upload',$_SERVER['DOCUMENT_ROOT']);
                            !file_exists($upload_dir) ? mkdir($upload_dir,'0755',true) : '';
                            $tempFile 			= $_FILES[$fileElementName]['tmp_name'];
                            $targetPath 		= $upload_dir . '/' . date('Y-m-d',time()).'/'.$user_id;
                            $guid = Service::create_guid();
                            !file_exists($targetPath) ? mkdir($targetPath,'0755',true) : '';
                            $temptargetfile 	= explode('.',$_FILES[$fileElementName]['name']);
                            $temptargetfile 	= $guid . '.' . $temptargetfile[count($temptargetfile)-1];
                            $targetFile 		= rtrim($targetPath,'/') . '/'. $temptargetfile;
                            move_uploaded_file($tempFile,$targetFile);

                            $uploadFile = new UploadFile();
                            $uploadFile->guid = $guid;
                            $uploadFile->filename = date('Y-m-d',time()).'/'.$user_id.'/'. $temptargetfile;
                            $uploadFile->filetype = $fileParts['extension'];
                            $uploadFile->original_filename = $_FILES[$fileElementName]['name'];
                            $uploadFile->upload_role = '注册商';
                            $uploadFile->company_id = $company_id;
                            $uploadFile->user_id = Yii::$app->user->id;
                            $uploadFile->ip = Util::get_ip();
                            $uploadFile->created = new Expression('NOW()');

                            if($uploadFile->save())
                            {
                                $array['name'] 	= $_FILES[$fileElementName]['name'];
                                $array['guid']  = $uploadFile->guid;
                                echo json_encode($array);exit;
                            } else {
                                $error = '上传失败';
                            }
                        } else {
                            $error = '超过大小限制';
                        }
                    } else {
                        $error = '不允许的文件格式';
                    }
                }
            }
            echo json_encode(array('error'=>$error));
        }
        /**
         * 根据其他条件如关联id获得model
         * @param string $models              模型名
         * @param string $array_temp          查询条件数组
         * @return $model
         */
        public function loadModelByAttr($models,$array_temp)
        {
            $model=$models::findOne($array_temp);
            if($model===null)
                Yii::$app->end();
            return $model;
        }
        /**
         * 预览上传文件
         * @author duan
         * @since 2014-11-28
         */
        public function actionShowuploadfile()
        {
            $id = isset($_GET['id']) ? $id = $_GET['id'] : Yii::$app->end();
            $model = $this->loadModelByAttr('app\models\UploadFile', array('guid'=>$id));
            $user_id = Yii::$app->user->id;
            if($user_id>0){
                $this->_out($model);
            }else{
                Yii::$app->end();
            }
        }

        private function _out($model)
        {
            //文件名
            $filename = $model->filename;
            //$filename = explode('/', $filename);
            //$filename = $filename[1];

            //文件全路径
            $upload_dir = Yii::$app->params['upload']['attachment_root_dir'];
            $filepath   = $upload_dir . '/' . $model->filename;
            //文件后缀名
            $file_extension = strtolower($model->filetype);
            $array = ['jpg','jpeg','png','bmp','gif','pdf','wav'];
            if (file_exists($filepath)){
                if(in_array($file_extension,$array))
                {
                    if($file_extension == 'jpg' || $file_extension == 'jpeg')
                    {
                        header("content-type:image/jpeg");
                    }else if($file_extension == 'png'){
                        header("content-type:image/png");
                    }else if($file_extension == 'gif'){
                        header("content-type:image/gif");
                    }elseif($file_extension == 'pdf'){
                        header("content-type:application/pdf");
                    }elseif($file_extension == 'wav'){

                    }
                    $handle  = fopen($filepath, "r");
                    $content = '';
                    while(!feof($handle)){ $content .= fread($handle, 8080); }
                    echo $content;
                    fclose($handle);
                }
                else {
                    yii::$app->response->sendFile ($upload_dir . '/' . $model->filename,$model->original_filename);
                }
            }
        }
    }