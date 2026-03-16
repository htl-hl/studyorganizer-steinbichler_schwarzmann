<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Teachers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$subjects = $model->teacher ? $model->teacher->subjects : [];

$this->registerJs(<<<JS
document.addEventListener('click', function (event) {
    const deleteLink = event.target.closest('.js-teacher-delete');
    if (!deleteLink) {
        return;
    }

    event.preventDefault();

    const deleteForm = document.getElementById('delete-teacher-form');
    const teacherNameLabel = document.getElementById('delete-teacher-name');
    const modalElement = document.getElementById('delete-teacher-modal');

    if (!deleteForm || !modalElement) {
        return;
    }

    deleteForm.setAttribute('action', deleteLink.getAttribute('href'));

    if (teacherNameLabel) {
        teacherNameLabel.textContent = deleteLink.getAttribute('data-teachername') || 'this teacher';
    }

    if (window.bootstrap && window.bootstrap.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
    }
});
JS
);
?>

<div class="teacher-view">

    <p class="mb-4">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->teacher->id], [
                'class' => 'btn btn-sm btn-outline-danger js-teacher-delete',
                'confirm' => 'Are you sure you want to delete this teacher?',
                'data-teachername' => $model->username,
        ]) ?>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= Html::encode($model->username) ?></h5>
            <span class="badge bg-secondary"><?= Html::encode($model->id) ?></span>
        </div>

        <div class="card-body">

            <ul class="list-group list-group-flush">

                <li class="list-group-item">
                    <strong>Username:</strong> <?= Html::encode($model->username) ?>
                </li>

                <li class="list-group-item">
                    <strong>Email:</strong> <?= Html::encode($model->email) ?>
                </li>

                <li class="list-group-item">
                    <strong>isActive:</strong> <?= Html::encode($model->teacher->getIsActiveAsString()) ?>
                </li>

                <li class="list-group-item">
                    <strong>Subjects:</strong>
                    <div class="mt-2">
                        <?php if (!empty($subjects)): ?>
                            <?php foreach ($subjects as $subject): ?>
                                <span class="badge bg-primary me-1 mb-1"><?= Html::encode($subject->name) ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="text-muted">No subjects assigned</span>
                        <?php endif; ?>
                    </div>
                </li>

            </ul>

        </div>

    </div>

    <?= $this->render('_deleteConfirm') ?>

</div>