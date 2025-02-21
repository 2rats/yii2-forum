<?php

use rats\forum\models\Forum;
use yii\widgets\ListView;

/**
 * @var Post $model
 * @var integer $index
 * @var ListView $widget
 */
?>

<div class="post row py-2 <?= $index % 2 == 0 ? 'bg-secondary' : 'bg-secondary-subtle' ?> <?= $index < $widget->dataProvider->getPagination()->pageSize - 1 ? 'border-bottom' : 'rounded-bottom-1' ?>">
    <div class="col-12 col-md-2">
        <h3 class="h5 m-0">
            <?php if ($model?->thread?->forum !== null): ?>
                <a class="small link-primary link-visited link-underline-opacity-0 link-underline-opacity-100-hover text-break" href="<?= $model?->thread?->forum->getUrl() ?>"><?= $model?->thread?->forum->name ?></a>
            <?php endif; ?>
        </h3>
    </div>
    <div class="col-12 col-md">
        <h3 class="h5 m-0">
            <a class="link-primary link-visited link-underline-opacity-0 link-underline-opacity-100-hover text-break" href="<?= $model?->thread->getUrl() ?>">
                <?= $model?->thread?->name ?>
            </a>
        </h3>
    </div>
    <div class="col-11 col-md-6 mx-auto border-top border-md-none mt-1 mt-md-auto position-relative my-auto" style="font-size: .9rem;">
        <a href="<?= $model->getUrl() ?>" class="stretched-link link-secondary link-visited link-underline-opacity-0">
            <div class="lines-3 small children-m-0 content">
                <?= $model->printContent(true) ?>
            </div>
            <div class="d-flex d-md-block justify-content-between">
                <p class="small mb-0 text-end fw-semibold"><?= $model->getCreatedAtString() ?></p>
            </div>
        </a>
    </div>
</div>