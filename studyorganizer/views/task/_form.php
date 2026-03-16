<?php

use app\models\User;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\ForbiddenHttpException;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/** @var yii\web\View $this */
/** @var app\models\Task $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'subjectId')->hiddenInput()->label(false) ?>

    <?php if ($model->subject): ?>
        <div class="alert alert-info mb-3">
            <i class="fas fa-book me-2"></i>
            <strong>For Subject:</strong> <?= Html::encode($model->subject->name) ?>
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dueDate')->widget(DateTimePicker::class, [
            'options' => ['placeholder' => 'Select due date...'],
            'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii',
                    'startDate' => date('Y-m-d H:i'),
                    'todayHighlight' => true,
            ]
    ]) ?>

    <?php
    if (!Yii::$app->user->isGuest) {
        if (Yii::$app->user->identity->isAdmin() || Yii::$app->user->identity->isTeacher()) {
            $users = ArrayHelper::map(User::find()->where(['role' => 'User'])->all(), 'id', 'username');
        } else {
            throw new ForbiddenHttpException('You are not allowed to access this page');
        }
    } ?>

    <?= $form->field($model, 'userIds')->widget(Select2::class, [
            'data' => $users,
            'options' => [
                    'placeholder' => 'Select users...',
                    'multiple' => true
            ],
            'pluginOptions' => [
                    'allowClear' => true
            ],
    ])->label('Assign to Users'); ?>
    <br>
    <div class="mb-3">
        <label for="taskDocumentFile" class="form-label fw-bold">
            Task description document
        </label>

        <div class="input-group">
        <span class="input-group-text">
            📎
        </span>
            <?= $form->field($model, 'taskDocumentFile')->fileInput([
                    'accept' => '.pdf,.doc,.docx,.md',
                    'id' => 'taskDocumentFile',
                    'class' => 'form-control'
            ])->label(false) ?>
        </div>

        <div class="form-text">
            pdf, word (doc, docx) or markdown files
        </div>
    </div>
    <br>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
