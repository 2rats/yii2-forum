<?php

use rats\forum\ForumModule;
use rats\forum\models\Forum;
use yii\helpers\Url;

/**
 * @var Forum $model
 * @var integer $index
 * @var ForumListWidget $widget
 * @var integer $key
 */
?>

<div class="forum row py-2 <?= $index % 2 == 0 ? 'bg-light' : 'bg-lighter' ?>">
    <div class="col">
        <h3 class="h5 m-0">
            <a class="link-secondary link-underline-opacity-0 link-underline-opacity-100-hover" href="<?= Url::to(['/' . ForumModule::getInstance()->id . "/forum/view", 'id' => $model->id, 'path' => $model->slug]) ?>"><?= $model->name ?></a>
        </h3>
        <div class="d-md-block d-none">
            <span class="small children-mb-0 lines-1"><?= $model->description ?></span>
        </div>
        <!-- Phone size -->
        <div class="d-md-none d-block small">
            <p class="mb-0">
                <span class="fw-medium"><?= Yii::t('app', 'Threads') ?>: </span><span><?= $model->threads ?></span> |
                <span class="fw-medium"><?= Yii::t('app', 'Posts') ?>: </span><span><?= $model->posts ?></span>
            </p>
            <?php if (!is_null($model->lastPost)) : ?>
                <p class="small mb-0 text-end"><span class="fw-bold"><?= $model->lastPost->printCreatedBy() ?></span> - <?= Yii::$app->formatter->asDatetime($model->lastPost->created_at) ?></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-auto">
        <div class="d-flex flex-column h-100 justify-content-center ">
            <?php if ($model->isLocked()) : ?>
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
                <span><?= $model->threads ?></span>
            </div>
            <div class="col-6 text-center">
                <p class="small fw-bold mb-1"><?= Yii::t('app', 'Posts') ?></p>
                <span><?= $model->posts ?></span>
            </div>
        </div>
    </div>
    <div class="col-3 d-md-block d-none my-auto last-post" style="font-size: .9rem;">
        <?php if (!is_null($model->lastPost)) : ?>
            <p class="small mb-0 fw-bold"><?= $model->lastPost->printCreatedBy() ?></p>
            <span class="lines-1 small children-m-0 content"><?= $model->lastPost->printContent() ?></span>
            <p class="small mb-0 text-end">- <?= Yii::$app->formatter->asDatetime($model->lastPost->created_at) ?></p>
        <?php endif; ?>
    </div>
</div>
