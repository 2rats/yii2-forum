<?php

/**
 * @var \yii\web\View $this
 * @var ActiveDataProvider $likeVoteDataProvider
 * @var ActiveDataProvider $dislikeVoteDataProvider
 * @var int|null $userId
 * @var \rats\forum\models\Post $post
 * @var bool $canVote
 * @var int $likeCount
 * @var int $dislikeCount
 * @var bool $userLiked
 * @var bool $userDisliked
 */

use rats\forum\ForumModule;
use rats\forum\models\Vote;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

?>

<?php Pjax::begin([
    'id' => 'pjaxVote' . $post->id,
    'enablePushState' => false,
    'linkSelector' => '#pjaxVote' . $post->id . ' a.vote',
]) ?>

<div class="d-flex justify-content-end align-items-center mt-2 py-1 small">
    <div class="px-2 d-flex align-items-center">
        <a href="<?= Url::to(['/' . ForumModule::getInstance()->id . '/post/vote', 'postId' => $post->id, 'value' => Vote::VALUE_LIKE]) ?>" class="vote d-flex align-items-center text-decoration-none <?= !$canVote ? 'pe-none' : '' ?> <?= $userLiked ? 'text-primary' : 'link-secondary' ?>">
            <i class="fas fa-thumbs-up fa-sm"></i>
            <?php if ($likeCount > 0): ?>
                <span class="ms-1 fw-semibold"><?= $likeCount ?></span>
            <?php endif; ?>
        </a>
    </div>

    <div class="px-2 d-flex align-items-center border-end">
        <a href="<?= Url::to(['/' . ForumModule::getInstance()->id . '/post/vote', 'postId' => $post->id, 'value' => Vote::VALUE_DISLIKE]) ?>" class="vote d-flex align-items-center text-decoration-none <?= !$canVote ? 'pe-none' : '' ?> <?= $userDisliked ? 'text-danger' : 'link-secondary' ?>">
            <i class="fas fa-thumbs-down fa-sm mt-1"></i>
            <?php if ($dislikeCount > 0): ?>
                <span class="ms-1 fw-semibold"><?= $dislikeCount ?></span>
            <?php endif; ?>
        </a>
    </div>

    <div class="px-2 d-flex align-items-center">
        <a href="#" class="d-flex align-items-center link-secondary text-decoration-none"
            data-bs-toggle="modal" data-bs-target="#votersListModal<?= $post->id ?>">
            <i class="fas fa-users fa-sm"></i>
            <span class="ms-1 small"><?= Yii::t('app', 'Votes') ?></span>
        </a>
    </div>
</div>

<div class="modal fade" id="votersListModal<?= $post->id ?>" tabindex="-1" aria-labelledby="votersListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-secondary border">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-users"></i> <?= Yii::t('app', 'People who voted') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="voteTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#likesList<?= $post->id ?>"><?= Yii::t('app', 'Likes') ?> (<?= $likeCount ?>)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#dislikesList<?= $post->id ?>"><?= Yii::t('app', 'Dislikes') ?> (<?= $dislikeCount ?>)</a>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    <div class="tab-pane fade show active" id="likesList<?= $post->id ?>">
                        <ul class="list-group">

                            <?php Pjax::begin([
                                'id' => 'pjaxVoteLike' . $post->id,
                                'enablePushState' => false,
                                'linkSelector' => '#pjaxVoteLike' . $post->id . ' .pagination a',
                            ]) ?>
                            <?= ListView::widget([
                                'dataProvider' => $likeVoteDataProvider,
                                'itemView' => '@rats/forum/widgets/views/_vote',
                                'layout' => '{items}<br>{pager}',
                                'emptyTextOptions' => [
                                    'class' => 'text-center text-secondary my-2',
                                ]
                            ]) ?>
                            <?php Pjax::end() ?>

                        </ul>
                    </div>

                    <div class="tab-pane fade" id="dislikesList<?= $post->id ?>">
                        <ul class="list-group">

                            <?php Pjax::begin([
                                'id' => 'pjaxVoteDislike' . $post->id,
                                'enablePushState' => false,
                                'linkSelector' => '#pjaxVoteDislike' . $post->id . ' .pagination a',
                            ]) ?>
                            <?= ListView::widget([
                                'dataProvider' => $dislikeVoteDataProvider,
                                'itemView' => '@rats/forum/widgets/views/_vote',
                                'layout' => '{items}<br>{pager}',
                                'emptyTextOptions' => [
                                    'class' => 'text-center text-secondary my-2',
                                ]
                            ]) ?>
                            <?php Pjax::end() ?>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php Pjax::end() ?>