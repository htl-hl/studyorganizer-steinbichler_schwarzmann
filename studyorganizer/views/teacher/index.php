<?php

use app\models\Teacher;
use yii\helpers\Html;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\TeacherSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Teachers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacher-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Teacher', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="row">
        <?php $teachers = $dataProvider->getModels(); ?>
        <?php if (empty($teachers)) : ?>
            <div class="col-12">
                <?php \yii\bootstrap5\Alert::widget([
                    'options' => [
                        'class' => 'alert-info',
                    ],
                    'body' => 'No teachers found.'
                ]); ?>
            </div>
        <?php else: ?>
            <?php foreach ($teachers as $teacher): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><?= Html::encode($teacher->firstname . ' ' . $teacher->lastname) ?></h5>
                            <span class="badge bg-secondary"><?= Html::encode($teacher->id) ?></span>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>First name:</strong> <?= Html::encode($teacher->firstname) ?>
                                </li>
                                <li class="list-group-item">
                                    <strong>Last name:</strong> <?= Html::encode($teacher->lastname) ?>
                                </li>
                                <li class="list-group-item">
                                    <strong>Active:</strong> <?= $teacher->isActive ? 'Yes' : 'No' ?>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer">
                            <?= Html::a('View', ['view', 'id' => $teacher->id], ['class' => 'btn btn-sm btn-outline-secondary', 'data-pjax' => '0']) ?>
                            <?= Html::a('Update', ['update', 'id' => $teacher->id], ['class' => 'btn btn-sm btn-outline-primary', 'data-pjax' => '0']) ?>
                            <?= Html::a('Delete', ['delete', 'id' => $teacher->id], [
                                'class' => 'btn btn-sm btn-outline-danger',
                                'data-pjax' => '0',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php Pjax::end(); ?>

</div>
