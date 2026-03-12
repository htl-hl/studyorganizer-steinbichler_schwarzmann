<?php

use app\models\User;
use yii\helpers\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs(<<<JS
document.addEventListener('click', function (event) {
    const deleteLink = event.target.closest('.js-user-delete');
    if (!deleteLink) {
        return;
    }

    event.preventDefault();

    const deleteForm = document.getElementById('delete-user-form');
    const userNameLabel = document.getElementById('delete-user-name');
    const modalElement = document.getElementById('delete-user-modal');

    if (!deleteForm || !modalElement) {
        return;
    }

    deleteForm.setAttribute('action', deleteLink.getAttribute('href'));

    if (userNameLabel) {
        userNameLabel.textContent = deleteLink.getAttribute('data-username') || 'this user';
    }

    if (window.bootstrap && window.bootstrap.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
    }
});
JS);
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Html::encode(Yii::$app->session->getFlash('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Html::encode(Yii::$app->session->getFlash('error')) ?>
        </div>
    <?php endif; ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'id',
            'username',
            'role',
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => static function ($url, User $model) {
                        return Html::a('View', ['view', 'id' => $model->id], [
                            'title' => 'View',
                            'data-pjax' => '0',
                        ]);
                    },
                    'update' => static function ($url, User $model) {
                        return Html::a('Update', ['update', 'id' => $model->id], [
                            'title' => 'Update',
                            'data-pjax' => '0',
                        ]);
                    },
                    'delete' => static function ($url, User $model) {
                        return Html::a('Delete', ['delete', 'id' => $model->id], [
                            'title' => 'Delete',
                            'class' => 'js-user-delete text-danger',
                            'data-pjax' => '0',
                            'data-username' => $model->username,
                        ]);
                    },
                ],
                'urlCreator' => static function ($action, User $model, $key) {
                    return [$action, 'id' => $model->id];
                },
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

    <?= $this->render('_deleteConfirm') ?>

</div>
