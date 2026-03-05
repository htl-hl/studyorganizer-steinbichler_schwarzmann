<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\bootstrap5\Html;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header class="border-bottom">
    <div class="container py-3 d-flex justify-content-between align-items-center">
        <div class="fw-bold">StudyOrganizer</div>
        <div class="d-flex gap-2 align-items-center">
            <?php if (Yii::$app->user->isGuest): ?>
                <?= Html::a('Login', ['/site/login'], ['class' => 'btn btn-primary btn-sm']) ?>
                <?= Html::a('Register', ['/site/register'], ['class' => 'btn btn-outline-primary btn-sm']) ?>
            <?php else: ?>
                <?= Html::a('Tasks', ['/task/index'], ['class' => 'btn btn-primary btn-sm']) ?>
                <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline']) ?>
                <?= Html::submitButton(
                    'Logout (' . Html::encode(Yii::$app->user->identity->username) . ')',
                    ['class' => 'btn btn-outline-secondary btn-sm']
                ) ?>
                <?= Html::endForm() ?>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="py-4">
    <div class="container">
        <?= $content ?>
    </div>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
