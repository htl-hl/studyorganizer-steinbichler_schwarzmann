<?php

use yii\bootstrap5\Alert;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\Subject[] $subjects */

$this->title = 'Subjects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()): ?>
            <?= Html::a('Create Subject', ['subject/create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </p>

    <?php Pjax::begin(); ?>

    <div class="row">
        <?php if (empty($subjects)) : ?>
            <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message): ?>
                <div class="col-12">
                    <?= Alert::widget([
                            'options' => ['class' => 'alert-' . $key],
                            'body' => $message,
                    ]) ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <?php foreach ($subjects

                           as $s): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><?= Html::encode($s->name) ?></h5>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge rounded-pill bg-dark"><?= Html::encode($s->id) ?></span>
                                <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()): ?>
                                    <?= Html::a($s->icondelete(), ['subject/delete', 'id' => $s->id], [
                                            'class' => 'btn btn-sm btn-outline-danger',
                                            'data' => [
                                                    'confirm' => 'Are you sure you want to delete this item?',
                                                    'method' => 'post',
                                            ],
                                    ]) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6>Tasks:</h6>
                            <?php if (!empty($s->tasks)): ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($s->tasks as $task): ?>
                                        <?php if (!Yii::$app->user->isGuest && (Yii::$app->user->identity->isAdmin() || Yii::$app->user->identity->teachesSubject($s->id) || Yii::$app->user->identity->hasTask($task->id))): ?>
                                            <li class="list-group-item"><?= Html::encode($task->description) ?></li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="empty-task">No tasks.</p>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <?php if (!Yii::$app->user->isGuest): ?>
                                <div class="mb-3">
                                    <?php if (Yii::$app->user->identity->isAdmin()): ?>
                                        <?= Html::a($s->iconupdate(), ['subject/update', 'id' => $s->id], ['class' => 'btn btn-sm btn-light']) ?>
                                    <?php endif; ?>

                                    <?php if ((Yii::$app->user->identity->teachesSubject($s->id)) || Yii::$app->user->identity->isAdmin()): ?>
                                        <?= Html::a('Create Task', ['task/create', 'subjectId' => $s->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    <?php Pjax::end(); ?>

</div>
