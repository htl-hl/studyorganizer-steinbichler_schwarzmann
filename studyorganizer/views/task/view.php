<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Task $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$assignedUsers = $model->users ?? [];

$this->registerJs(<<<JS
document.addEventListener('click', function (event) {
    const deleteLink = event.target.closest('.js-task-delete');
    if (!deleteLink) {
        return;
    }

    event.preventDefault();

    const deleteForm = document.getElementById('delete-task-form');
    const taskNameLabel = document.getElementById('delete-task-name');
    const modalElement = document.getElementById('delete-task-modal');

    if (!deleteForm || !modalElement) {
        return;
    }

    deleteForm.setAttribute('action', deleteLink.getAttribute('href'));

    if (taskNameLabel) {
        taskNameLabel.textContent = deleteLink.getAttribute('data-taskname') || 'this task';
    }

    if (window.bootstrap && window.bootstrap.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
    }
});
JS
);
?>

<div class="task-view">

    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

    <p class="mb-4">
        <?php if (!Yii::$app->user->isGuest && (Yii::$app->user->identity->isAdmin() || Yii::$app->user->identity->teachesSubject($model->subject->id))): ?>
        <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-sm btn-outline-danger js-task-delete',
                'confirm' => 'Are you sure you want to delete this task?',
                'data-taskname' => $model->title,
        ]) ?>
        <?php endif; ?>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= Html::encode($model->title) ?></h5>
            <span class="badge bg-secondary"><?= Html::encode($model->id) ?></span>
        </div>

        <div class="card-body">

            <ul class="list-group list-group-flush">

                <li class="list-group-item">
                    <strong>Due Date:</strong> <?= Html::encode($model->dueDate) ?>
                </li>

                <li class="list-group-item">
                    <strong>Completed:</strong>
                    <?= $model->isCompleted ?
                            '<span class="badge bg-success">Yes</span>' :
                            '<span class="badge bg-warning text-dark">No</span>' ?>
                </li>

                <li class="list-group-item">
                    <strong>Subject:</strong>
                    <?php if ($model->subject): ?>
                        <span class="badge bg-primary"><?= Html::encode($model->subject->name) ?></span>
                    <?php else: ?>
                        <span class="text-muted">No subject assigned</span>
                    <?php endif; ?>
                </li>
                <?php if (!Yii::$app->user->isGuest && (Yii::$app->user->identity->isAdmin() || Yii::$app->user->identity->teachesSubject($model->subject->id)  )): ?>
                    <li class="list-group-item">
                        <strong>Assigned Users:</strong>
                        <div class="mt-2">
                            <?php if (!empty($assignedUsers)): ?>
                                <?php foreach ($assignedUsers as $user): ?>
                                    <span class="badge bg-info text-dark me-1 mb-1"><?= Html::encode($user->username) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted">No users assigned</span>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endif; ?>

                <li class="list-group-item">
                    <strong>Description:</strong>
                    <p class="mt-1"><?= Html::encode($model->description) ?></p>
                </li>

            </ul>

        </div>

    </div>

    <?= $this->render('_deleteConfirm') ?>

</div>