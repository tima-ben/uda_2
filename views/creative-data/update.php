<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CreativeData */

$this->title = 'Update Creative Data: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Creative Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="creative-data-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
