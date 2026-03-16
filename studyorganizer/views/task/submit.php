<?php

/**@var app\models\Task $model */

/**@var app\models\TaskUser $taskUser */

use yii\helpers\Html;

$this->title = 'Submit Task: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Submit';
?>
<div class="task-submit">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_submitForm', [
            'model' => $model,
            'taskUser' => $taskUser
    ]) ?>
</div>

