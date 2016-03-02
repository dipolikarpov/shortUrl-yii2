<?php
namespace app\models\shortUrl\forms;

use Yii;
use yii\base\Model;

class ShortUrlForm extends Model {
    public $url;

    protected $errorMessage;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['url'], 'required', 'message' => 'Необходиво ввести URL'],
            ['url', 'validateUrl'],
        ];
    }

    /**
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateUrl($attribute, $params) {
        if (!$this->hasErrors()) {
            if (!$this->checkValidate()) {
                $this->addError($attribute, $this->errorMessage);
            }
        }
    }

    protected function checkValidate() {
        if(!filter_var($this->url,FILTER_VALIDATE_URL)){
            $this->errorMessage='Некоректный Url Адрес';
            return false;
        }
        if(filter_var($this->url,FILTER_VALIDATE_URL,FILTER_FLAG_PATH_REQUIRED)){
            return $this->checkExistence();
        }
        $this->errorMessage='Введенный Вами Url Адрес, достаточно простой';
        return false;
    }

    protected function checkExistence() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,  CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if(!empty($response) && $response !=404 ){
            return true;
        }
        $this->errorMessage='Введенный Вами адрес не существует';
        return false;
    }
}