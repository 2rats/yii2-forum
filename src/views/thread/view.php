<?php

/** @var yii\web\View $this */

use rats\forum\ForumModule;
use rats\forum\models\form\PostForm;
use rats\forum\models\Thread;
use yii\bootstrap5\LinkPager;
use yii\helpers\Url;


$this->title = $thread->name;
$this->params['breadcrumbs'][] = $thread->name;
$temp_forum = $thread->forum;
while ($temp_forum !== null) {
    array_unshift($this->params['breadcrumbs'], ['label' => $temp_forum->name, 'url' => Url::to(['/' . ForumModule::getInstance()->id . "/forum/view", 'id' => $temp_forum->id, 'path' => $temp_forum->slug])]);
    $temp_forum = $temp_forum->parent;
}

$this->registerCss('
.markdown-body {
    box-sizing: border-box;
    min-width: 200px;
    max-width: 980px;
    margin: 0 auto;

    background-color: transparent !important;
    color: #5e5e5e !important;
}

.markdown-body.small {
    font-size: 0.8rem;
}

@media (max-width: 767px) {
    .markdown-body {
        padding: 15px;
    }
}
');
?>
<!-- Github markdown styles -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/github-markdown-css/5.2.0/github-markdown-light.min.css" integrity="sha512-bm684OXnsiNuQSyrxuuwo4PHqr3OzxPpXyhT66DA/fhl73e1JmBxRKGnO/nRwWvOZxJLRCmNH7FII+Yn1JNPmg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="row justify-content-center my-3 post-container">

    <?php if (sizeof($posts) == 0) : ?>
        <div class="col-11 post border rounded text-secondary my-1">
            <div class="no-results row py-2 bg-light rounded-1">
                <div class="col-12 text-center"><?= Yii::t('app', 'No posts') ?></div>
            </div>
        </div>
    <?php endif; ?>
    <?php foreach ($posts as $index => $post) : ?>
        <div class="col-11 post border rounded-1 text-secondary my-1" id="post-<?= $post->id ?>">
            <div style="min-height: 20vh;" class="row bg-lighter rounded-1">
                <div class="py-2 col-12 col-md-2 border-md-end border-bottom border-md-bottom-0">
                    <p class="fw-bold m-0 text-center author">
                        <?= $post->printCreatedBy() ?>
                    </p>
                    <div class="d-flex justify-content-center">
                        <?php foreach ($post->createdByRoles as $role) : ?>
                            <small class="w-fit bg-light rounded-1 m-1 px-2 py-1 "><?= ucfirst($role->name) ?></small>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="py-2 col-12 col-md-10 bg-white rounded-end">
                    <div class="d-flex flex-column h-100">
                        <div class="border-bottom mb-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <span class=""><a class="link-secondary link-underline-opacity-0 link-underline-opacity-100-hover" href="<?= Url::to(['/' . ForumModule::getInstance()->id . '/thread/highlight', 'id' => $thread->id, 'path' => $thread->slug, 'post_id' => $post->id]); ?>">#<?= $index + 1 + (($pages->page) * 10) ?></a></span>
                                    <span class="small">- <?= Yii::$app->formatter->asDatetime($post->created_at) ?></span>
                                </div>
                                <div>
                                    <a data-post="<?= $post->id ?>" href="" class="reply-button link-secondary link-underline-opacity-0 link-underline-opacity-100-hover">
                                        <svg class="mb-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-reply-fill" viewBox="0 0 16 16">
                                            <path d="M5.921 11.9 1.353 8.62a.719.719 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.719V6c1.5 0 6 0 7 8-2.5-4.5-7-4-7-4v1.281c0 .56-.606.898-1.079.62z" />
                                        </svg>
                                        <?= Yii::t('app', 'Reply') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php if ($post->parent) : ?>
                            <div class="reply mx-5 small border-start border-3 p-2 mb-3 bg-lighter position-relative">
                                <a class="stretched-link" href="<?= Url::to(['/' . ForumModule::getInstance()->id . '/thread/highlight', 'id' => $thread->id, 'path' => $thread->slug, 'post_id' => $post->parent->id]); ?>"></a>
                                <div class="mb-1 d-flex">
                                    <span>
                                        <svg class="mb-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-reply-fill" viewBox="0 0 16 16">
                                            <path d="M5.921 11.9 1.353 8.62a.719.719 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.719V6c1.5 0 6 0 7 8-2.5-4.5-7-4-7-4v1.281c0 .56-.606.898-1.079.62z" />
                                        </svg>
                                    </span>
                                    <span class="fw-medium ms-1"><?= $post->parent->printCreatedBy() ?></span>
                                </div>
                                <div class="small markdown-body"><?= $post->parent->printContent(true) ?></div>
                            </div>
                        <?php endif; ?>
                        <div class="flex-grow-1">
                            <div class="content markdown-body" style="height: max-content"><?= $post->printContent() ?></div>
                        </div>
                        <?php if ($post->createdBy->signature) : ?>
                            <div class="p-2 pb-0 border-top small">
                                <small class="children-m-0">
                                    <?= $post->printCreatedBySignature() ?>
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div class="pagination justify-content-center">
    <?= LinkPager::widget([
        'pagination' => $pages,
    ]); ?>
</div>
<div class="post-add mb-5">
    <?php if (Yii::$app->user->can('forum-createPost') && $thread->status == Thread::STATUS_ACTIVE_UNLOCKED) : ?>
        <?= $this->render('/post/_form', [
            'post_form' => new PostForm(),
            'fk_thread' => $thread->id,
        ]); ?>
    <?php endif; ?>
    <?php if ($thread->status == Thread::STATUS_ACTIVE_LOCKED) : ?>
        <p class="small text-center text-secondary mb-0"><?= Yii::t('app', 'You can not post in a locked thread.') ?></p>
    <?php endif; ?>
    <?php if (Yii::$app->user->isGuest) : ?>
        <p class="small text-center text-secondary mb-0"><?= Yii::t('app', 'You need to login to post.') ?></p>
    <?php endif; ?>
</div>