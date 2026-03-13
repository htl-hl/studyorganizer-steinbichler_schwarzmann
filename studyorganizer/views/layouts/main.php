<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

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
    <?php
    if (!Yii::$app->user->isGuest) {
        NavBar::begin([
            'brandLabel' => 'StudyOrganizer',
            'brandUrl' => ['/task/index'],
            'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top'],
        ]);

        $leftNavItems = [
            ['label' => 'Tasks', 'url' => ['/task/index']],
        ];

        if (Yii::$app->user->identity->isAdmin()) {
            $leftNavItems[] = ['label' => 'Users', 'url' => ['/user/index']];
            $leftNavItems[] = ['label' => 'Teachers', 'url' => ['/teacher/index']];
        }

        $rightNavItems = [[
            'label' => 'Logout',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post'],
        ]];

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav me-auto'],
            'items' => $leftNavItems,
        ]);

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ms-auto'],
            'items' => $rightNavItems,
        ]);

        NavBar::end();
    }
    ?>
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
