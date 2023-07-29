<?php

/** @var yii\web\View $this */
/* @var string $content */

use app\widgets\Alert;
use rats\forum\ForumAsset;
use rats\forum\ForumModule;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

ForumAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

$this->title = $this->title ?? Yii::t('app', 'Forum');
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

    <header id="header">
        <?php
        NavBar::begin([
            'brandLabel' => 'yii2-forum',
            'brandUrl' => Url::to('/' . ForumModule::getInstance()->id),
            'options' => [
                'class' => 'navbar-expand-md navbar-dark position-sticky',
                'style' => 'background-color: var(--forum-primary-color)',
            ]
        ]);

        $navItems = [];

        if (Yii::$app->user->can('forum-admin') || Yii::$app->user->can('forum-moderator')) {
            $navItems[] = ['label' => Yii::t('app', 'Admin'), 'url' => ['/forum/admin/']];
        }

        $navItems[] =
            Yii::$app->user->isGuest
            ? ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login']]
            : '<li class="nav-item">'
            . Html::beginForm(['/site/logout'])
            . Html::submitButton(
                Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
                ['class' => 'nav-link btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => $navItems,
        ]);
        NavBar::end();
        ?>
    </header>

    <main id="main" class="flex-shrink-0" role="main">
        <?php if (!empty($this->params['breadcrumbs'])) : ?>
            <div class="bg-white shadow-sm">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-11">
                            <?= Breadcrumbs::widget([
                                'homeLink' => [
                                    'label' => Yii::t('app', 'Forum'),
                                    'url' => Url::to('/' . ForumModule::getInstance()->id)
                                ], 'links' => $this->params['breadcrumbs'],
                                'options' => [
                                    'class' => 'py-2 mb-0',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>
        <div class="container mt-3">
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer class="mt-auto py-3 bg-light">
        <div class="container">
            <div class="row text-muted">
                <div class="col-md-6 text-center text-md-start">&copy; <a href="https://github.com/2rats/yii2-forum">2rats/yii2-forum</a> <?= date('Y') ?></div>
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>