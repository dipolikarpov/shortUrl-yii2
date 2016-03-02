<?php

namespace app\controllers;

use app\models\shortUrl\forms\ShortUrlForm;
use app\models\shortUrl\UrlManager;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class ShortUrlController extends Controller {

    public $layout = 'shorturl';

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function actionIndex() {
        return $this->redirect('generate-short-url');
    }

    public function actionGenerateShortUrl(){
        $model=new ShortUrlForm();
        if (\Yii::$app->request->isAjax && $model->load(\Yii::$app->request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $result=ActiveForm::validate($model);
            if(count($result)>0){
                $response=[];
                $response['status']='error';
                $response['messages']=$result;
                return $response;
            }
            return '<input class="form-control" type="text" disabled value="'.UrlManager::getShortUrl($model).'">';
        }
        if ($model->load(\Yii::$app->request->post())) {
        }
        return $this->render('generateShortUrl',[
            'model' => $model,
            ]
        );
    }

    public function actionGoToShortUrl($url){
        print_r($url);
    }
}