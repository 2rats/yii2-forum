<?php

/** @var yii\web\View $this */

use rats\forum\models\Forum;
use rats\forum\ForumModule;
use rats\forum\models\Thread;
use rats\forum\models\User;
use yii\bootstrap5\LinkPager;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->params['breadcrumbs'][] = Yii::t('app', 'Hot Threads');
?>


<?php Pjax::begin([
    'scrollTo' => true,
    'linkSelector' => '.pagination a',
]) ?>

<div class="row justify-content-center my-3 mt-1">
    <div class="col-11 thread-container border rounded-1 text-secondary">
        <div class="row py-2 border-bottom rounded-top-1">
            <div class="col-12 px-3 py-2">
                <h3 class="text-dark fw-bold mb-0 text-decoration-underline"><?= Yii::t('app', 'Hot Threads') ?></h3>
            </div>
        </div>
        <div class="thread-header row py-2 border-bottom bg-lighter fw-bold rounded-top-1">
            <div class="col-2 border-end d-md-block d-none">
                <span class="mx-2"><?= Yii::t('app', 'Forum') ?></span>
            </div>
            <div class="col border-end">
                <span class="mx-2"><?= Yii::t('app', 'Thread') ?></span>
            </div>
            <div class="col-3 d-md-block d-none">
                <span class="mx-2"><?= Yii::t('app', 'Last post') ?></span>
            </div>
        </div>
        <?php if (empty($threads)) : ?>
            <div class="no-results row py-2 bg-light rounded-bottom-1">
                <div class="col-12 text-center"><?= Yii::t('app', 'No threads') ?></div>
            </div>
        <?php endif; ?>
        <?php foreach ($threads as $index => $thread) : ?>
            <div class="thread row py-2 <?= $index % 2 == 0 ? 'bg-light' : 'bg-lighter' ?> <?= $index < sizeof($threads) - 1 ? 'border-bottom' : 'rounded-bottom-1' ?>">
                <div class="col-2 d-md-block d-none">
                    <h3 class="h5 m-0">
                        <?php if ($thread->forum !== null): ?>
                            <a class="small link-secondary link-underline-opacity-0 link-underline-opacity-100-hover" href="<?= Url::to(['/' . ForumModule::getInstance()->id . "/forum/view", 'id' => $thread->forum->id, 'path' => $thread->forum->slug]) ?>"><?= $thread->forum->name ?></a>
                        <?php endif; ?>
                    </h3>
                </div>
                <div class="col">
                    <h3 class="h5 m-0">
                        <a class="link-secondary link-underline-opacity-0 link-underline-opacity-100-hover" href="<?= Url::to(['/' . ForumModule::getInstance()->id . "/thread/view", 'id' => $thread->id, 'path' => $thread->slug]) ?>"><?= $thread->name ?></a>
                    </h3>
                    <p class="small mb-0"><span class="fw-bold"><?= $thread->getCreatedByHtml() ?></span> - <?= $thread->getCreatedAtString() ?></p>
                    <!-- Phone size -->
                    <div class="d-md-none d-block small">
                        <p class="mb-0">
                            <span class="fw-medium"><?= Yii::t('app', 'Posts') ?>: </span><span><?= $thread->posts ?></span>
                            <span class="fw-medium"><?= Yii::t('app', 'Views') ?>: </span><span><?= $thread->views ?></span>
                        </p>
                        <?php if (!is_null($thread->lastPost)) : ?>
                            <p class="small mb-0 text-end"><span class="fw-bold"><?= $thread->lastPost->getCreatedByHtml() ?></span> - <?= $thread->lastPost->getCreatedAtString() ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="d-flex flex-column h-100 justify-content-center ">
                        <?php if ($thread->isLocked()) : ?>
                            <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        <?php endif; ?>
                        <?php if ($thread->isPinned()) : ?>
                            <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                            </svg>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-3 d-md-block d-none">
                    <div class="row gx-2 h-100 align-items-center">
                        <div class="col-6 text-center border-end">
                            <p class="small fw-bold mb-1"><?= Yii::t('app', 'Posts') ?></p>
                            <span><?= $thread->posts ?></span>
                        </div>
                        <div class="col-6 text-center">
                            <p class="small fw-bold mb-1"><?= Yii::t('app', 'Views') ?></p>
                            <span><?= $thread->views ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-3 d-md-block d-none my-auto last-post" style="font-size: .9rem;">
                    <?php if (!is_null($thread->lastPost)) : ?>
                        <p class="small mb-0 fw-bold"><?= $thread->lastPost->getCreatedByHtml() ?></p>
                        <span class="lines-1 small children-m-0 content"><?= $thread->lastPost->printContent() ?></span>
                        <p class="small mb-0 text-end">- <?= $thread->lastPost->getCreatedAtString() ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="pagination justify-content-center">
    <?= LinkPager::widget([
        'pagination' => $pages,
    ]); ?>
</div>

<?php Pjax::end() ?>