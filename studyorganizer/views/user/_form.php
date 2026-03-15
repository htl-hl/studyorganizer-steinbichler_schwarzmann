<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use \app\models\Subject;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */

$roleFormUrl = Yii::$app->request->get('role');
?>

    <div class="user-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput(['placeholder' => 'Enter username']) ?>
        <?= $form->field($model, 'email')->textInput(['placeholder' => 'example@gmail.com']) ?>
        <?= $form->field($model, 'password')->passwordInput(['placeholder' => '123456 is not safe']) ?>

        <?php if ($roleFormUrl !== 'Teacher'): ?>

            <?= $form->field($model, 'role')->dropDownList(
                    ['Admin' => 'Admin', 'User' => 'User', 'Teacher' => 'Teacher'],
                    ['id' => 'role-selector']
            ) ?>

        <?php else: ?>

            <?= $form->field($model, 'role')->hiddenInput()->label(false) ?>

        <?php endif; ?>

        <div id="teacher-isActive-field" style="display:none;">
            <?= $form->field($model, 'isActive', [
                    'options' => ['class' => 'mt-3 mb-3']
            ])->checkbox(
                    ['label' => 'Is the teacher active?']
            ) ?>
        </div>

        <div id="teacher-subjects-field" style="display:none;">
            <?= $form->field($model, 'subjectIds')->listBox(
                    ArrayHelper::map(Subject::find()->all(), 'id', 'name'),
                    ['multiple' => true, 'size' => 5]
            )->label('Assign Subjects To Teacher') ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php
$this->registerJs(<<<JS

var roleSelector = document.getElementById('role-selector');

function toggleTeacherfields() {
    var subjects_field = document.getElementById('teacher-subjects-field');
    var isActive_field = document.getElementById('teacher-isActive-field');
    
    if (roleSelector) {
        var isTeacher = roleSelector.value;
        subjects_field.style.display = isTeacher === 'Teacher' ? 'block' : 'none';
        isActive_field.style.display = isTeacher === 'Teacher' ? 'block' : 'none'
    } else {
        subjects_field.style.display = 'block';
        isActive_field.style.display = 'block';
    }
}   

toggleTeacherfields();


if (roleSelector) {
    roleSelector.addEventListener('change', toggleTeacherfields);
}

JS
);
?>