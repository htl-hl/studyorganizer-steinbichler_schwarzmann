<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\RegisterForm $model */

$this->title = 'Create Teacher';
$this->params['breadcrumbs'][] = ['label' => 'Teachers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacher-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_createForm', [
        'model' => $model,
    ]) ?>

</div>
