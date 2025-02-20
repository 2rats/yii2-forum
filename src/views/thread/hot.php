<?php

/**
 * @var yii\web\View $this
 * @var yii\data\Pagination $pages
 * @var Category|null $category
 */

use rats\forum\models\Category;
use yii\bootstrap5\LinkPager;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->params['breadcrumbs'][] = Yii::t('app', 'Hot Threads');
?>


<?php Pjax::begin([
    'scrollTo' => true,
    'linkSelector' => '.pagination a, a.category-link',
]) ?>

<div class="row justify-content-center my-3 mt-1">
    <div class="col-11 thread-container border rounded-1 text-secondary">
        <div class="d-flex gap-2 justify-content-between px-3 py-2 border-bottom rounded-top-1">
            <div class="py-2">
                <h3 class="text-dark fw-bold mb-0 text-decoration-underline"><?= Yii::t('app', 'Hot Threads') ?></h3>
                <div class="row gy-1 mt-1">
                    <a
                        href="<?= Url::to(['', 'category' => false]) ?>"
                        class="category-link col btn btn-sm me-1 text-nowrap <?= !$category ? 'disabled btn-primary' : 'btn-outline-primary' ?>">
                        <?= Yii::t('app', 'All categories') ?>
                    </a>
                    <?php foreach (Category::find()->active()->ordered()->all() as $cat): ?>
                        <a
                            href="<?= Url::to(['', 'category' => $cat->id]) ?>"
                            class="category-link col btn btn-sm me-1 text-nowrap <?= $cat->id == $category ? 'disabled btn-primary' : 'btn-outline-primary' ?>">
                            <?= $cat->name ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="thread-header row py-2 border-bottom text-bg-primary">
            <div class="col-2 border-md-end d-md-block d-none">
                <span class="mx-2 fw-bold"><?= Yii::t('app', 'Forum') ?></span>
            </div>
            <div class="col border-md-end">
                <span class="mx-2 fw-bold"><?= Yii::t('app', 'Thread') ?></span>
            </div>
            <div class="col-3 d-md-block d-none">
                <span class="mx-2 fw-bold"><?= Yii::t('app', 'Last post') ?></span>
            </div>
        </div>
        <?php if (empty($threads)): ?>
            <div class="no-results row py-2 bg-secondary-subtle rounded-bottom-1">
                <div class="col-12 text-center"><?= Yii::t('app', 'No threads') ?></div>
            </div>
        <?php endif; ?>
        <?php foreach ($threads as $index => $thread): ?>
            <div class="thread row py-2 <?= $index % 2 == 0 ? 'bg-white' : 'bg-secondary-subtle' ?> <?= $index < sizeof($threads) - 1 ? 'border-bottom' : 'rounded-bottom-1' ?>">
                <div class="col-2 d-md-block d-none">
                    <h3 class="h5 m-0">
                        <?php if ($thread->forum !== null): ?>
                            <a class="small link-primary link-visited link-underline-opacity-0 link-underline-opacity-100-hover text-break" href="<?= $thread->forum->getUrl() ?>"><?= $thread->forum->name ?></a>
                        <?php endif; ?>
                    </h3>
                </div>
                <div class="col">
                    <h3 class="h5 m-0">
                        <a class="link-primary link-visited link-underline-opacity-0 link-underline-opacity-100-hover text-break" href="<?= $thread->getUrl() ?>"><?= $thread->name ?></a>
                    </h3>
                    <!-- Phone size -->
                    <div class="d-md-none d-block small">
                        <p class="mb-0">
                            <span class="fw-medium"><?= Yii::t('app', 'Posts') ?>: </span><span><?= Yii::$app->formatter->asInteger($thread->posts) ?></span>
                            <span class="fw-medium"><?= Yii::t('app', 'Views') ?>: </span><span><?= Yii::$app->formatter->asInteger($thread->views) ?></span>
                        </p>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="d-flex flex-column h-100 justify-content-center ">
                        <?php if ($thread->isLocked()): ?>
                            <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        <?php endif; ?>
                        <?php if ($thread->isPinned()): ?>
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
                            <span><?= Yii::$app->formatter->asInteger($thread->posts) ?></span>
                        </div>
                        <div class="col-6 text-center">
                            <p class="small fw-bold mb-1"><?= Yii::t('app', 'Views') ?></p>
                            <span><?= Yii::$app->formatter->asInteger($thread->views) ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-11 mx-auto border-top border-md-none mt-1 mt-md-auto col-md-3 position-relative my-auto last-post" style="font-size: .9rem;">
                    <?php if ($thread->lastPost !== null): ?>
                        <a href="<?= $thread->lastPost->getUrl() ?>" class="stretched-link link-primary link-visited link-underline-opacity-0">
                            <div class="lines-1 small children-m-0 content">
                                <?= $thread->lastPost->printContent(true) ?>
                            </div>
                            <div class="d-flex d-md-block justify-content-between">
                                <div class="fw-bold small text-secondary"><?= $thread->lastPost?->createdBy->getDisplayName() ?></div>
                                <p class="small mb-0 text-end"><?= $thread->lastPost->getCreatedAtString() ?></p>
                            </div>
                        </a>
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