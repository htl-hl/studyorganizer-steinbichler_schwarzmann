<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var app\models\Teacher $teacher*/

$this->title = 'Update Teacher: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Teachers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="teacher-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'teacher' => $teacher
    ]) ?>

</div>
