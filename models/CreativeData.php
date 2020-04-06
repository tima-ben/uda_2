<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zz__yashi_creative_data".
 *
 * @property int $id
 * @property int|null $creative_id
 * @property int|null $log_date
 * @property int|null $impression_count
 * @property int|null $click_count
 * @property int|null $25viewed_count
 * @property int|null $50viewed_count
 * @property int|null $75viewed_count
 * @property int|null $100viewed_count
 *
 * @property Creative $creative
 */
class CreativeData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zz__yashi_creative_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['creative_id', 'log_date', 'impression_count', 'click_count', '25viewed_count', '50viewed_count', '75viewed_count', '100viewed_count'], 'integer'],
            [['creative_id', 'log_date'], 'unique', 'targetAttribute' => ['creative_id', 'log_date']],
            [['creative_id'], 'exist', 'skipOnError' => true, 'targetClass' => Creative::className(), 'targetAttribute' => ['creative_id' => 'creative_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'creative_id' => 'Creative ID',
            'log_date' => 'Date',
            'impression_count' => 'Impressions',
            'click_count' => 'Clicks',
            '25viewed_count' => '25% Viewed',
            '50viewed_count' => '50% Viewed',
            '75viewed_count' => '75% Viewed',
            '100viewed_count' => '100% Viewed',
        ];
    }

    /**
     * Gets query for [[Creative]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreative()
    {
        return $this->hasOne(Creative::className(), ['creative_id' => 'creative_id']);
    }
}
