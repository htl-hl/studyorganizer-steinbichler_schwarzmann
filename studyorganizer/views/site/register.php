<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Register';
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= $form->field($model, 'username')->textInput(['placeholder' => 'Enter a Username']) ?>

        <?php if ($model->hasErrors('username')): ?>
            <p class="error"><?= implode(', ', $model->getFirstErrors('username')) ?></p>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'email')->textInput(['placeholder' => 'example@gmail.com']) ?>

        <?php if ($model->hasErrors('email')): ?>
            <p class="error"><?= implode(', ', $model->getFirstErrors('email')) ?></p>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'password')->passwordInput(['placeholder' => '123456 is not safe']) ?>

        <?php if ($model->hasErrors('password')): ?>
            <p class="error"><?= implode(', ', $model->getFirstErrors('password')) ?></p>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Register', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Return', ['login'], ['class' => 'btn btn-secondary'])?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<style>
    .error {
        color: red;
        margin-top: 5px;
        font-size: 0.9em;
    }
</style>
