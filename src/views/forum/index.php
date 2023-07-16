<?php

/** @var yii\web\View $this */
?>

<div class="row justify-content-center">
    <div class="col-11 forum-container border rounded text-secondary">
        <div class="forum-header row py-2 border-bottom bg-light fw-bold">
            <div class="col-6 border-end">
                <span class="mx-2"><?= Yii::t('app', 'Forum') ?></span>
            </div>
            <div class="col-3 border-end">
                <span class="mx-2"><?= Yii::t('app', 'Statistics') ?></span>
            </div>
            <div class="col-3">
                <span class="mx-2"><?= Yii::t('app', 'Last post') ?></span>
            </div>
        </div>
        <?php foreach ($forums as $index => $forum) : ?>
            <div class="forum row py-2 <?= $index % 2 == 0 ? 'bg-lighter' : 'bg-light' ?> <?= $index < sizeof($forums) - 1 ? 'border-bottom' : false ?>">
                <div class="col-6">
                    <h3 class="h5 m-0"><?= $forum->name ?></h3>
                    <p class="small mb-0"><?= $forum->description ?></p>
                </div>
                <div class="col-3">
                    <div class="row h-100 align-items-center">
                        <div class="col-6 text-center">
                            <p class="small fw-bold mb-1"><?= Yii::t('app', 'Threads') ?></p>
                            <span><?= $forum->getThreads(false)->count() ?></span>
                        </div>
                        <div class="col-6 text-center">
                            <p class="small fw-bold mb-1"><?= Yii::t('app', 'Posts') ?></p>
                            <span><?= $forum->getPosts(false)->count() ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-3" style="font-size: .9rem;">
                    <?php if (!is_null($forum->lastPost)) : ?>
                        <p class="small mb-0 fw-bold"><?= $forum->lastPost->createdBy->username ?></p>
                        <span class="lines-1 small"><?= $forum->lastPost->content ?></span>
                        <p class="small mb-0 text-end">- <?= Yii::$app->formatter->asDatetime($forum->lastPost->created_at) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>