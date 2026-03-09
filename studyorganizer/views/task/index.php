<?php

use app\models\Task;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\Subject[] $subjects */

$this->title = 'Subjects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Task', ['create'], ['class' => 'btn btn-primary']) ?>
        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()): ?>
            <?= Html::a('Create Subject', ['subject/create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </p>

    <?php Pjax::begin(); ?>

    <div class="row">
        <?php if (empty($subjects)) : ?>
            <div class="col-12">
                <?php \yii\bootstrap5\Alert::widget([
                        'options' => [
                                'class' => 'alert-info',
                        ],
                        'body' => 'No subjects found.'
                ]); ?>
            </div>
        <?php else: ?>
            <?php foreach ($subjects as $s): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><?= Html::encode($s->name) ?></h5>
                            <span class="badge bg-secondary"><?= Html::encode($s->id) ?></span>
                        </div>
                        <div class="card-body">
                            <?php if (!Yii::$app->user->isGuest && Yii::$app->user->can('Admin')): ?>
                                <div class="mb-3">
                                    <?= Html::a($s->iconupdate(), ['subject/update', 'id' => $s->id], ['class' => 'btn btn-sm btn-light']) ?>
                                    <?= Html::a($s->icondelete(), ['subject/delete', 'id' => $s->id], [
                                        'class' => 'btn btn-sm btn-light',
                                        'data' => [
                                            'confirm' => 'Are you sure you want to delete this item?',
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                </div>
                            <?php endif; ?>
                            <h6>Tasks:</h6>
                            <?php if (!empty($s->tasks)): ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($s->tasks as $task): ?>
                                        <li class="list-group-item"><?= Html::encode($task->description) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-muted">No tasks.</p>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <?= Html::a('Create Task', ['task/create', 'subjectId' => $s->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    <?php Pjax::end(); ?>

</div>
