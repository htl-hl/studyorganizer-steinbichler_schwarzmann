<?php

use yii\helpers\Html;
use yii\web\YiiAsset;

/** @var yii\web\View $this */
/** @var app\models\Subject $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Subjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);

$this->registerJs(<<<JS
document.addEventListener('click', function (event) {
    const deleteLink = event.target.closest('.js-subject-delete');
    if (!deleteLink) {
        return;
    }

    event.preventDefault();

    const deleteForm = document.getElementById('delete-subject-form');
    const taskNameLabel = document.getElementById('delete-subject-name');
    const modalElement = document.getElementById('delete-subject-modal');

    if (!deleteForm || !modalElement) {
        return;
    }

    deleteForm.setAttribute('action', deleteLink.getAttribute('href'));

    if (taskNameLabel) {
        taskNameLabel.textContent = deleteLink.getAttribute('data-subjectname') || 'this Subject';
    }

    if (window.bootstrap && window.bootstrap.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
    }
});
JS
);
?>

<div class="subject-view">

    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

    <p class="mb-4">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-sm btn-outline-danger js-subject-delete',
                'data-subjectname' => $model->name,
        ]) ?>
        <?= Html::a('Back', ['task/index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= Html::encode($model->name) ?></h5>
            <span class="badge bg-secondary"><?= Html::encode($model->id) ?></span>
        </div>

        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Name:</strong> <?= Html::encode($model->name) ?>
                </li>
            </ul>
        </div>

    </div>

    <?= $this->render('_deleteConfirm') ?>

</div>
