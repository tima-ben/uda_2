<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zz__yashi_order".
 *
 * @property int $order_id
 * @property int|null $campaign_id
 * @property int|null $yashi_order_id
 * @property string|null $name
 *
 * @property Creative[] $creatives
 * @property Campaign $campaign
 * @property OrderData[] $orderDatas
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zz__yashi_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaign_id', 'yashi_order_id'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['campaign_id'], 'exist', 'skipOnError' => true, 'targetClass' => Campaign::className(), 'targetAttribute' => ['campaign_id' => 'campaign_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'campaign_id' => 'Campaign ID',
            'yashi_order_id' => 'Ext. Order ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Creatives]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatives()
    {
        return $this->hasMany(Creative::className(), ['order_id' => 'order_id']);
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

    /**
     * Gets query for [[OrderDatas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDatas()
    {
        return $this->hasMany(OrderData::className(), ['order_id' => 'order_id']);
    }
}
