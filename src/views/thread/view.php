<?php

/** @var yii\web\View $this */

use rats\forum\ForumModule;
use yii\bootstrap5\LinkPager;
use yii\helpers\Url;


$this->title = $thread->name;
$this->params['breadcrumbs'][] = $thread->name;
$temp_forum = $thread->forum;
while ($temp_forum !== null) {
    array_unshift($this->params['breadcrumbs'], ['label' => $temp_forum->name, 'url' => Url::to('/' . ForumModule::getInstance()->id . "/{$temp_forum->slug}/{$temp_forum->id}")]);
    $temp_forum = $temp_forum->parent;
}
?>

<div class="row justify-content-center my-3 post-container">
    <?php foreach ($posts as $index => $post) : ?>
        <div class="col-11 post border rounded text-secondary my-1" id="post-<?= $post->id ?>">
            <div style="min-height: 20vh;" class="row bg-light rounded">
                <div class="py-2 col-12 col-md-2 border-md-end border-bottom border-md-bottom-0">
                    <p class="fw-bold m-0 text-center">
                        <?= $post->printCreatedBy() ?>
                    </p>
                    <div class="d-flex justify-content-center">
                        <?php foreach ($post->createdByRoles as $role) : ?>
                            <small class="w-fit bg-lighter rounded-1 m-1 px-2 py-1 "><?= ucfirst($role->name) ?></small>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="py-2 col-12 col-md-10 bg-white rounded-end">
                    <div class="d-flex flex-column h-100">
                        <div class="border-bottom mb-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <span class=""><a class="link-secondary link-underline-opacity-0 link-underline-opacity-100-hover" href="/<?= ForumModule::getInstance()->id . '/thread/' . $thread->slug . '/' . $thread->id . '/' . $post->id ?>">#<?= $index + 1 + (($pages->page) * 10) ?></a></span>
                                    <span class="small">- <?= Yii::$app->formatter->asDatetime($post->created_at) ?></span>
                                </div>
                            </div>
                        </div>
                        <?php if ($post->parent) : ?>
                            <div class="reply ms-5 small col-11 border-start border-3 p-2 mb-3 bg-light position-relative">
                                <a class="stretched-link" href="/<?= ForumModule::getInstance()->id . '/thread/' . $thread->slug . '/' . $thread->id . '/' . $post->parent->id ?>"></a>
                                <p class="mb-1"><span class=""><svg class="mb-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-reply-fill" viewBox="0 0 16 16">
                                            <path d="M5.921 11.9 1.353 8.62a.719.719 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.719V6c1.5 0 6 0 7 8-2.5-4.5-7-4-7-4v1.281c0 .56-.606.898-1.079.62z" />
                                        </svg>
                                    </span> <span class="fw-medium"><?= $post->parent->printCreatedBy() ?></span></p>
                                <p class="small mb-0"><?= $post->parent->printContent() ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="flex-grow-1">
                            <p class="h-100s" style="height: max-content"><?= $post->printContent() ?></p>
                        </div>
                        <?php if ($post->createdBy->signature) : ?>
                            <div class="py-1 border-top ">
                                <p class="mt-auto small mb-0"><?= $post->printCreatedBySignature() ?></p>
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