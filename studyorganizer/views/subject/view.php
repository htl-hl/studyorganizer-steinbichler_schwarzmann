<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Subject $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Subjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);

$this->registerJs(<<<JS
document.addEventListener('click', function (event) {
    const deleteLink = event.target.closest('.js-subject-delete');
    if (!deleteLink) {
        return;
    }

    event.preventDefault();

    const deleteForm = document.getElementById('delete-subject-form');
    const taskNameLabel = document.getElementById('delete-subject-name');
    const modalElement = document.getElementById('delete-subject-modal');

    if (!deleteForm || !modalElement) {
        return;
    }

    deleteForm.setAttribute('action', deleteLink.getAttribute('href'));

    if (taskNameLabel) {
        taskNameLabel.textContent = deleteLink.getAttribute('data-subjectname') || 'this Subject';
    }

    if (window.bootstrap && window.bootstrap.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
    }
});
JS
);
?>
<div class="subject-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-sm btn-outline-danger js-subject-delete',
                'confirm' => 'Are you sure you want to delete this subject?',
                'data-subjectname' => $model->name,
        ]) ?>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
        ],
    ]) ?>

    <?= $this->render('_deleteConfirm') ?>


</div>
