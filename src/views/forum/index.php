<?php

/** @var yii\web\View $this */
/** @var Forum[] $forums */
/** @var bool $subforum */

use rats\forum\ForumModule;
use rats\forum\models\Forum;
use yii\helpers\Url;

?>

<div class="row justify-content-center mb-4">
    <div class="col-11 forum-container border rounded-1 text-secondary">
        <div class="forum-header row py-2 border-bottom bg-lighter fw-bold rounded-top-1">
            <div class="col-12 col-md-9 border-end">
                <span class="mx-2"><?= $subforum ? Yii::t('app', 'Subforum') : Yii::t('app', 'Forum') ?></span>
            </div>
            <div class="col-3 d-md-block d-none">
                <span class="mx-2"><?= Yii::t('app', 'Last post') ?></span>
            </div>
        </div>
        <?php if (sizeof($forums) == 0) : ?>
            <div class="no-results row py-2 bg-light rounded-bottom-1">
                <div class="col-12 text-center"><?= Yii::t('app', 'No forums') ?></div>
            </div>
        <?php endif; ?>
        <?php foreach ($forums as $index => $forum) : ?>
            <div class="forum row py-2 <?= $index % 2 == 0 ? 'bg-light' : 'bg-lighter' ?> <?= $index < sizeof($forums) - 1 ? 'border-bottom' : 'rounded-bottom-1' ?>">
                <div class="col">
                    <h3 class="h5 m-0">
                        <a class="link-secondary link-underline-opacity-0 link-underline-opacity-100-hover" href="<?= Url::to('/' . ForumModule::getInstance()->id . "/{$forum->slug}/{$forum->id}") ?>"><?= $forum->name ?></a>
                    </h3>
                    <p class="small mb-0 d-md-block d-none"><?= $forum->description ?></p>
                    <!-- Phone size -->
                    <div class="d-md-none d-block small">
                        <p class="mb-0">
                            <span class="fw-medium"><?= Yii::t('app', 'Threads') ?>: </span><span><?= $forum->threads ?></span> |
                            <span class="fw-medium"><?= Yii::t('app', 'Posts') ?>: </span><span><?= $forum->posts ?></span>
                        </p>
                        <?php if (!is_null($forum->lastPost)) : ?>
                            <p class="small mb-0 text-end"><span class="fw-bold"><?= $forum->lastPost->printCreatedBy() ?></span> - <?= Yii::$app->formatter->asDatetime($forum->lastPost->created_at) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="d-flex flex-column h-100 justify-content-center ">
                        <?php if ($forum->status == Forum::STATUS_ACTIVE_LOCKED) : ?>
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
                            <span><?= $forum->threads ?></span>
                        </div>
                        <div class="col-6 text-center">
                            <p class="small fw-bold mb-1"><?= Yii::t('app', 'Posts') ?></p>
                            <span><?= $forum->posts ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-3 d-md-block d-none my-auto last-post" style="font-size: .9rem;">
                    <?php if (!is_null($forum->lastPost)) : ?>
                        <p class="small mb-0 fw-bold"><?= $forum->lastPost->printCreatedBy() ?></p>
                        <span class="lines-1 small children-m-0 content"><?= $forum->lastPost->printContent() ?></span>
                        <p class="small mb-0 text-end">- <?= Yii::$app->formatter->asDatetime($forum->lastPost->created_at) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>