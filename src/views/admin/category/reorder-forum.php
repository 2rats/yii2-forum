<?php

/** @var yii\web\View $this */
/* @var Forum[] $forums */
/* @var bool $subforum */

use rats\forum\ForumModule;
use rats\forum\models\Forum;
use yii\helpers\Url;

?>

<div class="row justify-content-center mb-4" style="display: none;">
    <div class=" col-11 forum-container border rounded-1 text-secondary child-items">
        <?php foreach ($forums as $index => $forum) : ?>
            <div class="forum child row py-2 bg-lighter border-bottom ?>" data-id=<?= $forum->id ?>>
                <div class="col">
                    <h3 class="h5 m-0">
                        <a class="link-secondary link-underline-opacity-0 link-underline-opacity-100-hover" href="<?= $forum->getUrl() ?>"><?= $forum->name ?></a>
                    </h3>
                    <p class="small mb-0 d-md-block d-none"><?= $forum->description ?></p>
                    <!-- Phone size -->
                    <div class="d-md-none d-block small">
                        <p class="mb-0">
                            <span class="fw-medium"><?= Yii::t('app', 'Threads') ?>: </span><span><?= Yii::$app->formatter->asInteger($forum->threads) ?></span> |
                            <span class="fw-medium"><?= Yii::t('app', 'Posts') ?>: </span><span><?= Yii::$app->formatter->asInteger($forum->posts) ?></span>
                        </p>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="d-flex flex-column h-100 justify-content-center ">
                        <?php if ($forum->isLocked()) : ?>
                            <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-3 d-md-block d-none">
                    <div class="row gx-2 h-100 align-items-center">
                        <div class="col-6 text-center border-end">
                            <p class="small fw-bold mb-1"><?= Yii::t('app', 'Threads') ?></p>
                            <span><?= Yii::$app->formatter->asInteger($forum->threads) ?></span>
                        </div>
                        <div class="col-6 text-center">
                            <p class="small fw-bold mb-1"><?= Yii::t('app', 'Posts') ?></p>
                            <span><?= Yii::$app->formatter->asInteger($forum->posts) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
