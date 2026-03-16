<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">

    <h1 class="mb-2"><?= Html::encode($this->title) ?></h1>
    <p class="text-muted mb-0">Please fill out the following fields to login.</p>
</div>
<div class="card shadow-sm">
    <div class="card-body p-4">

        <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'form-label'],
                        'inputOptions' => ['class' => 'form-control'],
                        'errorOptions' => ['class' => 'invalid-feedback d-block'],
                ],
        ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"form-check\">{input} {label}</div>\n<div>{error}</div>",
                'labelOptions' => ['class' => 'form-check-label'],
                'inputOptions' => ['class' => 'form-check-input'],
        ]) ?>

        <div class="d-grid mt-3">
            <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <hr class="my-4">

        <p class="text-center text-muted mb-3">Don't have an account yet?</p>
        <div class="d-grid gap-2">
            <?= Html::a('Sign Up', ['register'], ['class' => 'btn btn-outline-primary']) ?>
                <?php // Teacher login is handled by normal login via role. ?>
        </div>

    </div>
</div>
