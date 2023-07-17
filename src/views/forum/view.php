<?php

/** @var yii\web\View $this */

use rats\forum\models\Forum;
use rats\forum\ForumModule;
use yii\helpers\Url;

$this->title = $forum->name;
$this->params['breadcrumbs'][] = $forum->name;
$temp_forum = $forum;
while ($temp_forum->parent !== null) {
    array_unshift($this->params['breadcrumbs'], ['label' => $temp_forum->parent->name, 'url' => Url::to('/' . ForumModule::getInstance()->id . "/{$temp_forum->parent->slug}/{$temp_forum->parent->id}")]);
    $temp_forum = $temp_forum->parent;
}
?>

<?php if ($forum->forums) : ?>
    <?= $this->render('index', [
        'forums' => $forum->getForums()->andWhere([
            'status' => [
                Forum::STATUS_ACTIVE_LOCKED,
                Forum::STATUS_ACTIVE_UNLOCKED
            ]
        ])->all(),
        'subforum' => true
    ]); ?>
<?php endif; ?>

<div class="row justify-content-center my-3">
    <div class="col-11 thread-container border rounded text-secondary">
        <div class="thread-header row py-2 border-bottom bg-light fw-bold rounded-top">
            <div class="col-12 col-md-9 border-end">
                <span class="mx-2"><?= Yii::t('app', 'Thread') ?></span>
            </div>
            <div class="col-3 d-md-block d-none">
                <span class="mx-2"><?= Yii::t('app', 'Last post') ?></span>
            </div>
        </div>
        <?php if (sizeof($threads) == 0) : ?>
            <div class="no-results row py-2 bg-lighter rounded-bottom">
                <div class="col-12 text-center"><?= Yii::t('app', 'No threads') ?></div>
            </div>
        <?php endif; ?>
        <?php foreach ($threads as $index => $thread) : ?>
            <div class="thread row py-2 <?= $index % 2 == 0 ? 'bg-lighter' : 'bg-light' ?> <?= $index < sizeof($threads) - 1 ? 'border-bottom' : 'rounded-bottom' ?>">
                <div class="col-12 col-md-6">
                    <h3 class="h5 m-0">
                        <a class="link-secondary link-underline-opacity-0 link-underline-opacity-100-hover" href="<?= Url::to('/' . ForumModule::getInstance()->id . "/thread/{$thread->slug}/{$thread->id}") ?>"><?= $thread->name ?></a>
                    </h3>
                    <p class="small mb-0"><span class="fw-bold"><?= $thread->createdBy->username ?></span> - <?= Yii::$app->formatter->asDatetime($thread->created_at) ?></p>
                    <!-- Phone size -->
                    <div class="d-md-none d-block small">
                        <p class="mb-0">
                            <span class="fw-medium"><?= Yii::t('app', 'Posts') ?>: </span><span><?= $thread->getPosts(false)->count() ?></span>
                            <span class="fw-medium"><?= Yii::t('app', 'Views') ?>: </span><span><?= $thread->views ?></span>
                        </p>
                        <?php if (!is_null($thread->lastPost)) : ?>
                            <p class="small mb-0 text-end"><span class="fw-bold"><?= $thread->lastPost->printCreatedBy() ?></span> - <?= Yii::$app->formatter->asDatetime($thread->lastPost->created_at) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-3 d-md-block d-none">
                    <div class="row gx-2 h-100 align-items-center">
                        <div class="col-6 text-center border-end">
                            <p class="small fw-bold mb-1"><?= Yii::t('app', 'Posts') ?></p>
                            <span><?= $thread->getPosts(false)->count() ?></span>
                        </div>
                        <div class="col-6 text-center">
                            <p class="small fw-bold mb-1"><?= Yii::t('app', 'Views') ?></p>
                            <span><?= $thread->views ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-3 d-md-block d-none my-auto" style="font-size: .9rem;">
                    <?php if (!is_null($thread->lastPost)) : ?>
                        <p class="small mb-0 fw-bold"><?= $thread->lastPost->printCreatedBy() ?></p>
                        <span class="lines-1 small"><?= $thread->lastPost->printContent() ?></span>
                        <p class="small mb-0 text-end">- <?= Yii::$app->formatter->asDatetime($thread->lastPost->created_at) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>