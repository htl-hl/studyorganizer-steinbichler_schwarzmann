<?php

use yii\bootstrap5\Alert;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Teachers';
$this->params['breadcrumbs'][] = $this->title;

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

<div class="teacher-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Teacher', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
        echo Alert::widget([
                'options' => ['class' => "alert-{$type}"],
                'body' => $message,
        ]);
    }
    ?>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="row">
        <?php $teachers = $dataProvider->getModels(); ?>
        <?php if (empty($teachers)) : ?>
            <div class="col-12">
                <?php try {
                    echo Alert::widget([
                            'options' => ['class' => 'alert-info'],
                            'body' => 'There are no teachers registered!'
                    ]);
                } catch (Throwable $e) {
                    echo $e;
                } ?>
            </div>
        <?php else: ?>
            <?php foreach ($teachers as $teacher): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><?= Html::encode($teacher->user->username) ?></h5>
                            <span class="badge bg-secondary"><?= Html::encode($teacher->user->id) ?></span>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>Username:</strong> <?= Html::encode($teacher->user->username) ?>
                                </li>
                                <li class="list-group-item">
                                    <strong>Email:</strong> <?= Html::encode($teacher->user->email) ?>
                                </li>
                                <li class="list-group-item">
                                    <strong>isActive:</strong> <?= Html::encode($teacher->getIsActiveAsString()) ?>
                                </li>
                                <li class="list-group-item">
                                    <strong>Subjects:</strong>
                                    <?php foreach ($teacher->subjects as $subject): ?>
                                        <span class="badge bg-info"><?= Html::encode($subject->name) ?></span>
                                    <?php endforeach; ?>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer">
                            <?= Html::a('View', ['view', 'id' => $teacher->user->id], ['class' => 'btn btn-sm btn-outline-secondary', 'data-pjax' => '0']) ?>
                            <?= Html::a('Update', ['update', 'id' => $teacher->user->id], ['class' => 'btn btn-sm btn-outline-primary', 'data-pjax' => '0']) ?>
                            <?= Html::a('Delete', ['delete', 'id' => $teacher->id], [
                                    'class' => 'btn btn-sm btn-outline-danger js-teacher-delete',
                                    'data-pjax' => '0',
                                    'data-teachername' => $teacher->user->username,
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
