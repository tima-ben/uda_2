<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Creative */

$this->title = 'Update Creative: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Creatives', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->creative_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="creative-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
