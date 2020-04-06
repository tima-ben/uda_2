<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zz__yashi_advertiser".
 *
 * @property int $yashi_advertiser_id
 * @property string|null $advertiser_name
 */
class Advertiser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zz__yashi_advertiser';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['yashi_advertiser_id'], 'required'],
            [['yashi_advertiser_id'], 'integer'],
            [['advertiser_name'], 'string', 'max' => 100],
            [['yashi_advertiser_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'yashi_advertiser_id' => 'Ext. Advertiser ID',
            'advertiser_name' => 'Advertiser Name',
        ];
    }

    /**
     * This method return list of all yashi_advertiser_id
     * @return string[]
     */
    static function getListYasiId()
    {
        $list = []; 
        $rows = self::find()->all();
        foreach($rows as $row)
        {
            $list[] = (string) $row->yashi_advertiser_id;
        }
        return $list;
    }
}
