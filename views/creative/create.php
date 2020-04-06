<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Creative */

$this->title = 'Create Creative';
$this->params['breadcrumbs'][] = ['label' => 'Creatives', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="creative-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
