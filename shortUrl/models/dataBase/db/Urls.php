<?php
namespace app\models\dataBase\db;

use Yii;

/**
 * This is the model class for table "urls".
 *
 * @property integer $id
 * @property string $url
 * @property integer $short_url_id
 *
 * @property ShortUrl $shortUrl
 */
class Urls extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'urls';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['short_url_id'], 'integer'],
            [['url'], 'string', 'max' => 250],
            [['url'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'short_url_id' => 'Short Url ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShortUrl()
    {
        return $this->hasOne(ShortUrl::className(), ['id' => 'short_url_id']);
    }
}
