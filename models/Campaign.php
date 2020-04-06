<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zz__yashi_cgn".
 *
 * @property int $campaign_id
 * @property int|null $yashi_campaign_id
 * @property string|null $name
 * @property int|null $yashi_advertiser_id
 * @property string|null $advertiser_name
 *
 * @property CampaignData[] $campaignDatas
 * @property Order[] $orders
 */
class Campaign extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zz__yashi_cgn';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['yashi_campaign_id', 'yashi_advertiser_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['advertiser_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'campaign_id' => 'Campaign ID',
            'yashi_campaign_id' => 'Ext. Campaign ID',
            'name' => 'Name',
            'yashi_advertiser_id' => 'Ext. Advertiser ID',
            'advertiser_name' => 'Advertiser Name',
        ];
    }

    /**
     * Gets query for [[CampaignDatas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCampaignDatas()
    {
        return $this->hasMany(CampaignData::className(), ['campaign_id' => 'campaign_id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['campaign_id' => 'campaign_id']);
    }
}
