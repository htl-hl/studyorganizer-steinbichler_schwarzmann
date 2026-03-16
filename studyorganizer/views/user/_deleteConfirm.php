<?php

use yii\bootstrap5\Modal;
use yii\helpers\Html;

Modal::begin([
    'id' => 'delete-user-modal',
    'title' => 'Delete User',
]);
?>
<p>Do you really want to delete <strong id="delete-user-name">this user</strong>?</p>

<div class="d-flex gap-2 justify-content-end mt-3">
    <?= Html::button('Cancel', [
        'class' => 'btn btn-secondary',
        'data-bs-dismiss' => 'modal',
    ]) ?>

    <?= Html::beginForm('', 'post', ['id' => 'delete-user-form']) ?>
    <?= Html::submitButton('Delete', ['class' => 'btn btn-danger w-100']) ?>
    <?= Html::endForm() ?>
</div>
<?php Modal::end(); ?>
