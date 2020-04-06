<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zz__yashi_cgn_data".
 *
 * @property int $id
 * @property int|null $campaign_id
 * @property int|null $log_date
 * @property int|null $impression_count
 * @property int|null $click_count
 * @property int|null $25viewed_count
 * @property int|null $50viewed_count
 * @property int|null $75viewed_count
 * @property int|null $100viewed_count
 *
 * @property Campaign $campaign
 */
class CampaignData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zz__yashi_cgn_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaign_id', 'log_date', 'impression_count', 'click_count', '25viewed_count', '50viewed_count', '75viewed_count', '100viewed_count'], 'integer'],
            [['campaign_id', 'log_date'], 'unique', 'targetAttribute' => ['campaign_id', 'log_date']],
            [['campaign_id'], 'exist', 'skipOnError' => true, 'targetClass' => Campaign::className(), 'targetAttribute' => ['campaign_id' => 'campaign_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'campaign_id' => 'Campaign ID',
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
     * Gets query for [[Campaign]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCampaign()
    {
        return $this->hasOne(Campaign::className(), ['campaign_id' => 'campaign_id']);
    }
}
