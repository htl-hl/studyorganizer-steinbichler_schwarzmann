<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs(<<<JS
document.addEventListener('click', function (event) {
    const deleteLink = event.target.closest('.js-user-delete');
    if (!deleteLink) {
        return;
    }

    event.preventDefault();

    const deleteForm = document.getElementById('delete-user-form');
    const userNameLabel = document.getElementById('delete-user-name');
    const modalElement = document.getElementById('delete-user-modal');

    if (!deleteForm || !modalElement) {
        return;
    }

    deleteForm.setAttribute('action', deleteLink.getAttribute('href'));

    if (userNameLabel) {
        userNameLabel.textContent = deleteLink.getAttribute('data-username') || 'this user';
    }

    if (window.bootstrap && window.bootstrap.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
    }
});
JS
);
?>

<div class="user-view">

    <p class="mb-4">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-sm btn-outline-danger js-user-delete',
                'confirm' => 'Are you sure you want to delete this user?',
                'data-username' => $model->username
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
                    <strong>Role:</strong> <?= Html::encode($model->role) ?>
                </li>

            </ul>

        </div>

    </div>

    <?= $this->render('_deleteConfirm') ?>

</div>