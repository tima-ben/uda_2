<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zz__yashi_creative".
 *
 * @property int $creative_id
 * @property int|null $order_id
 * @property int|null $yashi_creative_id
 * @property string|null $name
 * @property string|null $preview_url
 *
 * @property Order $order
 * @property CreativeData[] $creativeDatas
 */
class Creative extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zz__yashi_creative';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'yashi_creative_id'], 'integer'],
            [['name', 'preview_url'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'order_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'creative_id' => 'Creative ID',
            'order_id' => 'Order ID',
            'yashi_creative_id' => 'Ext. Creative ID',
            'name' => 'Name',
            'preview_url' => 'Preview Url',
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

    /**
     * Gets query for [[CreativeDatas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreativeDatas()
    {
        return $this->hasMany(CreativeData::className(), ['creative_id' => 'creative_id']);
    }
}
