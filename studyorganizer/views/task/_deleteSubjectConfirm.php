<?php

use yii\bootstrap5\Modal;
use yii\helpers\Html;

Modal::begin([
    'id' => 'delete-subject-modal',
    'title' => 'Delete Subject',
]);
?>
<p>Do you really want to delete <strong id="delete-subject-name">this subject</strong>?</p>

<div class="d-flex gap-2 justify-content-end mt-3">
    <?= Html::button('Cancel', [
        'class' => 'btn btn-secondary',
        'data-bs-dismiss' => 'modal',
    ]) ?>

    <?= Html::beginForm('', 'post', ['id' => 'delete-subject-form']) ?>
    <?= Html::submitButton('Delete', ['class' => 'btn btn-danger w-100']) ?>
    <?= Html::endForm() ?>
</div>
<?php Modal::end(); ?>
