<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zz__yashi_order_data".
 *
 * @property int $id
 * @property int|null $order_id
 * @property int|null $log_date
 * @property int|null $impression_count
 * @property int|null $click_count
 * @property int|null $25viewed_count
 * @property int|null $50viewed_count
 * @property int|null $75viewed_count
 * @property int|null $100viewed_count
 *
 * @property Order $order
 */
class OrderData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zz__yashi_order_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'log_date', 'impression_count', 'click_count', '25viewed_count', '50viewed_count', '75viewed_count', '100viewed_count'], 'integer'],
            [['order_id', 'log_date'], 'unique', 'targetAttribute' => ['order_id', 'log_date']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'order_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
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
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['order_id' => 'order_id']);
    }
}
