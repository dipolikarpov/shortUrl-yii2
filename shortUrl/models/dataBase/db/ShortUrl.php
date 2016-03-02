<?php
namespace app\models\dataBase\db;

use Yii;

/**
 * This is the model class for table "short_url".
 *
 * @property integer $id
 * @property string $short_url
 *
 * @property Urls[] $urls
 */
class ShortUrl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'short_url';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['short_url'], 'string', 'max' => 10],
            [['short_url'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'short_url' => 'Short Url',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUrls()
    {
        return $this->hasMany(Urls::className(), ['short_url_id' => 'id']);
    }
}
