<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CreativeData */

$this->title = 'Create Creative Data';
$this->params['breadcrumbs'][] = ['label' => 'Creative Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="creative-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
