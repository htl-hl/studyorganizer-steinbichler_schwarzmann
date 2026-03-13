<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['placeholder' => 'Enter username'])?>

    <?= $form->field($model, 'email')->textInput(['placeholder' => 'example@gmail.com'])?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => '123456 is not safe'])?>

    <?= $form->field($model, 'role')->dropDownList(
            ['Admin' => 'Admin', 'User' => 'User', 'Teacher' => 'Teacher']
    )?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
