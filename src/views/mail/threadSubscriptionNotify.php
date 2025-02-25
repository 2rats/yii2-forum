<?php

use rats\forum\models\Post;
use rats\forum\models\Thread;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var Thread $thread
 * @var Post $post
 */
?>

<div class="new-post-notification">
    <h2><?= Yii::t('app', 'Thread') ?>: <?= Html::encode($thread->name) ?></h2>

    <p>
        <?= Yii::t('app', 'A new post has been added to the thread you are subscribed to.') ?>
        <?= Yii::t('app', 'To view the full post, click the link below.') ?>
    </p>

    <p>
        <?= Html::a($post->getUrl([], true), $post->getUrl([], true)) ?>
    </p>

    <br>

    <p>
        <small>
            <?= Yii::t('app', 'You can manage all your subscriptions in your profile.') ?>
        </small>
    </p>
</div>