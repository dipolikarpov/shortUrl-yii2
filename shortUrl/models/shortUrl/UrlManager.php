<?php
namespace app\models\shortUrl;

use app\models\dataBase\db\ShortUrl;
use app\models\dataBase\db\Urls;
use app\models\shortUrl\forms\ShortUrlForm;
use yii\base\Exception;
use yii\helpers\Url;

class UrlManager {
    protected static $chars = 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ';


    public static function getShortUrl(ShortUrlForm $model) {

        if (!$model instanceof ShortUrlForm) {
            throw new Exception('"$model" не является экземпляром класса "ShortUrlForm"');
        }
        $i = 0;
        $urlModel = Urls::find()->joinWith('shortUrl')->where(['url' => $model->url])->one();
        while (is_null($urlModel)) {
            $i++;
            if ($i > 10) {
                throw new Exception('Превышено количество попыток получения короткого url');
            }
            self::setShortUrl($model);
            $urlModel = Urls::find()->joinWith('shortUrl')->where(['url' => $model->url])->one();
        }
        if(is_null($urlModel->short_url_id)){
            self::setShortUrl($model,true);
            $urlModel = Urls::find()->joinWith('shortUrl')->where(['url' => $model->url])->one();
        }
        return Url::canonical().$urlModel->shortUrl->short_url;
    }

    public static function getRealUrl() {

    }


    protected static function setShortUrl(ShortUrlForm $model, $exist = false) {
        if (!$model instanceof ShortUrlForm) {
            throw new Exception('"$model" не является экземпляром класса "ShortUrlForm"');
        }
        if (!$exist) {
            $urlModel = new Urls();
            $urlModel->url = $model->url;
            $urlModel->save();
            if (is_null($urlModel->id)) {
                throw new Exception('Не удалось сделать запись в базу данных');
            }
        }else{
            $urlModel = Urls::find()->joinWith('shortUrl')->where(['url' => $model->url])->one();
        }
        $shortUrl = self::generateShortUrl();

        $shortUrlModel = new ShortUrl();
        $shortUrlModel->short_url = $shortUrl;
        do {
            $shortUrlModel->save();
        } while (is_null($shortUrlModel->id));

        $urlModel->short_url_id = $shortUrlModel->id;
        $urlModel->save();
    }

    public static function generateShortUrl($iteration = 0) {
        $iteration++;
        if ($iteration > 10) {
            throw new Exception('Превышено количество попыток получения короткого url');
        }
        $length = strlen(self::$chars);
        if ($length < 10) {
            throw new \Exception("Длина строки мала");
        }

        $id = rand(100, time()) * $length;
        $code = "";
        while ($id > $length - 1) {
            $key = (int)fmod($id, $length);
            $code .= self::$chars[$key];
            $id = (int)floor($id / $length);
        }
        $shortUrlModel = ShortUrl::find()->where(['short_url' => $code])->one();
        if (!is_null($shortUrlModel)) {
            self::generateShortUrl($iteration);
        }
        return $code;
    }
}