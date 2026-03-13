<?php

use app\models\User;
use yii\helpers\Html;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Users';
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
JS);
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Html::encode(Yii::$app->session->getFlash('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Html::encode(Yii::$app->session->getFlash('error')) ?>
        </div>
    <?php endif; ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="row">
        <?php $users = $dataProvider->getModels(); ?>
        <?php if (empty($users)) : ?>
            <div class="col-12">
                <?php \yii\bootstrap5\Alert::widget([
                    'options' => [
                        'class' => 'alert-info',
                    ],
                    'body' => 'No users found.'
                ]); ?>
            </div>
        <?php else: ?>
            <?php foreach ($users as $user): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><?= Html::encode($user->username) ?></h5>
                            <span class="badge bg-secondary"><?= Html::encode($user->id) ?></span>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>Role:</strong> <?= Html::encode($user->role) ?>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer">
                            <?= Html::a('View', ['view', 'id' => $user->id], ['class' => 'btn btn-sm btn-outline-secondary', 'data-pjax' => '0']) ?>
                            <?= Html::a('Update', ['update', 'id' => $user->id], ['class' => 'btn btn-sm btn-outline-primary', 'data-pjax' => '0']) ?>
                            <?= Html::a('Delete', ['delete', 'id' => $user->id], [
                                'class' => 'btn btn-sm btn-outline-danger js-user-delete',
                                'data-pjax' => '0',
                                'data-username' => $user->username,
                            ]) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php Pjax::end(); ?>

    <?= $this->render('_deleteConfirm') ?>

</div>
