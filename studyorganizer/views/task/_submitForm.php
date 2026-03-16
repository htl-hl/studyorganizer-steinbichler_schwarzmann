<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Task $model */
/** @var app\models\TaskUser $taskUser */
?>

<div class="task-submit">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Submission for: <?= Html::encode($model->title) ?></h5>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Subject:</strong> <?= Html::encode($model->subject->name) ?>
                </li>
                <li class="list-group-item">
                    <strong>Due Date:</strong> <?= Html::encode($model->dueDate) ?>
                </li>
                <li class="list-group-item">
                    <strong>Description:</strong>
                    <p class="mt-2 mb-0"><?= Html::encode($model->description) ?></p>
                </li>

                <li class="list-group-item">
                    <?php $form = ActiveForm::begin([
                            'options' => ['enctype' => 'multipart/form-data'],
                            'action' => ['task/submit-return', 'id' => $model->id]
                    ]) ?>

                    <?= $form->field($taskUser, 'returnDocumentFile')->fileInput([
                            'accept' => '.pdf,.doc,.docx,.md',
                            'class' => 'form-control'
                    ])->label('📎 Upload your submission file') ?>


                    <div class="alert alert-warning mt-3" role="alert">
                        <strong>⚠️ Important:</strong>
                        Once submitted, this assignment <strong>cannot be undone or changed!</strong>
                        Please double-check your file before submitting.
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <?= Html::submitButton('📤 Submit task', [
                                'class' => 'btn btn-success',
                                'onclick' => 'return confirm("Are you sure? This cannot be undone!")'
                        ]) ?>
                        <?= Html::a('Cancel', ['view', 'id' => $model->id], [
                                'class' => 'btn btn-secondary'
                        ]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </li>
            </ul>
        </div>
    </div>
</div>
