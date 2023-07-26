<?php

/** @var yii\web\View $this */

use rats\forum\models\Forum;
use rats\forum\ForumModule;
use rats\forum\models\Thread;
use yii\bootstrap5\LinkPager;
use yii\helpers\Url;

$this->title = $forum->name;
$this->params['breadcrumbs'][] = $forum->name;
$temp_forum = $forum;
while ($temp_forum->parent !== null) {
    array_unshift($this->params['breadcrumbs'], ['label' => $temp_forum->parent->name, 'url' => Url::to('/' . ForumModule::getInstance()->id . "/{$temp_forum->parent->slug}/{$temp_forum->parent->id}")]);
    $temp_forum = $temp_forum->parent;
}

function getSortLink($sort, $label, $name)
{
    $class = 'link-secondary link-underline-opacity-0 link-underline-opacity-100-hover ';
    $sort_attr = str_replace('-', '', Yii::$app->request->get('sort', ''));
    if ($sort_attr == $name) {
        $carret_up = '<svg class="ms-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-up-fill" viewBox="0 0 16 16"> <path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/> </svg>';
        $carret_down = '<svg class="ms-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16"> <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/> </svg>';
        $class .= 'fw-medium';
        if (mb_substr(Yii::$app->request->get('sort', ''), 0, 1) == '-') {
            $label .= $carret_down;
        } else {
            $label .= $carret_up;
        }
    }
    return $sort->link($name, [
        'class' => $class,
        'label' => $label
    ]);
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

<div class="sort px-5">
    <span><?= Yii::t('app', 'Sort by') ?></span>
    <?= getSortLink($sort, Yii::t('app', 'Name'), 'name') ?> |
    <?= getSortLink($sort, Yii::t('app', 'Posts'), 'posts') ?> |
    <?= getSortLink($sort, Yii::t('app', 'Views'), 'views') ?> |
    <?= getSortLink($sort, Yii::t('app', 'Date'), 'created_at') ?>
</div>
<div class="row justify-content-center my-3 mt-1">
    <div class="col-11 thread-container border rounded-1 text-secondary">
        <div class="thread-header row py-2 border-bottom bg-lighter fw-bold rounded-top-1">
            <div class="col-12 col-md-9 border-end">
                <span class="mx-2"><?= Yii::t('app', 'Thread') ?></span>
            </div>
            <div class="col-3 d-md-block d-none">
                <span class="mx-2"><?= Yii::t('app', 'Last post') ?></span>
            </div>
        </div>
        <?php if (sizeof($threads) == 0) : ?>
            <div class="no-results row py-2 bg-light rounded-bottom-1">
                <div class="col-12 text-center"><?= Yii::t('app', 'No threads') ?></div>
            </div>
        <?php endif; ?>
        <?php foreach ($threads as $index => $thread) : ?>
            <div class="thread row py-2 <?= $index % 2 == 0 ? 'bg-light' : 'bg-lighter' ?> <?= $index < sizeof($threads) - 1 ? 'border-bottom' : 'rounded-bottom-1' ?>">
                <div class="col">
                    <h3 class="h5 m-0">
                        <a class="link-secondary link-underline-opacity-0 link-underline-opacity-100-hover" href="<?= Url::to('/' . ForumModule::getInstance()->id . "/thread/{$thread->slug}/{$thread->id}") ?>"><?= $thread->name ?></a>
                    </h3>
                    <p class="small mb-0"><span class="fw-bold"><?= $thread->createdBy->username ?></span> - <?= Yii::$app->formatter->asDatetime($thread->created_at) ?></p>
                    <!-- Phone size -->
                    <div class="d-md-none d-block small">
                        <p class="mb-0">
                            <span class="fw-medium"><?= Yii::t('app', 'Posts') ?>: </span><span><?= $thread->posts ?></span>
                            <span class="fw-medium"><?= Yii::t('app', 'Views') ?>: </span><span><?= $thread->views ?></span>
                        </p>
                        <?php if (!is_null($thread->lastPost)) : ?>
                            <p class="small mb-0 text-end"><span class="fw-bold"><?= $thread->lastPost->printCreatedBy() ?></span> - <?= Yii::$app->formatter->asDatetime($thread->lastPost->created_at) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="d-flex flex-column h-100 justify-content-center ">
                        <?php if ($thread->status == Thread::STATUS_ACTIVE_LOCKED) : ?>
                            <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        <?php endif; ?>
                        <?php if ($thread->pinned == Thread::PINNED_TRUE) : ?>
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
<div class="pagination justify-content-center">
    <?= LinkPager::widget([
        'pagination' => $pages,
    ]); ?>
</div>