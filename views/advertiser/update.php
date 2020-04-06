<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Advertiser */

$this->title = 'Update Advertiser: ' . $model->yashi_advertiser_id;
$this->params['breadcrumbs'][] = ['label' => 'Advertisers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->yashi_advertiser_id, 'url' => ['view', 'id' => $model->yashi_advertiser_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="advertiser-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
