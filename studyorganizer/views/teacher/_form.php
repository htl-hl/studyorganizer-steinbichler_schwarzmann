<?php

use app\models\Subject;
use kartik\select2\Select2;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var app\models\Teacher $teacher */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="teacher-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => '123456 is not safe']) ?>

    <?= $form->field($teacher, 'removeTeacherStatus', [
            'options' => ['class' => 'mt-3 mb-3']
    ])->checkbox([
            'label' => 'Remove teacher status?',
            'id' => 'remove-teacher-status'
    ]) ?>

    <div id="teacher-elements">
        <?= $form->field($teacher, 'isActive', [
                'options' => ['class' => 'mt-3 mb-3']
        ])->checkbox(
                ['label' => 'Is the teacher active?']
        ) ?>

        <?= $form->field($model, 'subjectIds')->widget(Select2::class, [
                'data' => ArrayHelper::map(Subject::find()->all(), 'id', 'name'),
                'options' => [
                        'placeholder' => 'Select subjects...',
                        'multiple' => true
                ],
                'pluginOptions' => [
                        'allowClear' => true
                ],
        ])->label('Assign Subjects To Teacher'); ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', ['view', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs(<<<JS

    var statusRemoveCheckBox = document.getElementById('remove-teacher-status');
    var teacherElements = document.getElementById('teacher-elements');
    
    function toggleTeacherElements() {
        teacherElements.style.display = statusRemoveCheckBox.checked ? 'none' : 'block';
    }
    toggleTeacherElements();
    statusRemoveCheckBox.addEventListener('change', toggleTeacherElements);
JS
);
?>
