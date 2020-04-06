<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Order Datas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-data-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Order Data', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'order_id',
            'log_date' => [
                'attribute' => 'log_date',
                'value' =>function ($data) { return date('Y-m-d',$data->log_date); }
            ],
            'impression_count',
            'click_count',
            '25viewed_count',
            '50viewed_count',
            '75viewed_count',
            '100viewed_count',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
