<?php

/** @var yii\web\View $this */
/** @var User[] $members */

use rats\forum\widgets\MembersListWidget;
use yii\widgets\Pjax;

?>

<div class="container text-secondary">

<div class="row justify-content-center mb-2">
    <div class="col-11">
        <h3 class="mb-0"><?= Yii::t('app', 'Forum Users') ?></h3>
    </div>
</div>

<div class="row justify-content-center mb-4">
    <div class="col-11 border rounded-1 text-secondary">
        <div class="forum-header row py-2 border-bottom bg-lighter fw-bold rounded-top-1">
            <div class="col-6 border-end">
                <span class="mx-2"><?= Yii::t('app', 'Username') ?></span>
            </div>
            <div class="col-2 d-md-block d-none">
                <span class="mx-2"><?= Yii::t('app', 'Posts') ?></span>
            </div>
            <div class="col-4 border-start ">
                <span class="mx-2"><?= Yii::t('app', 'Joined') ?></span>
            </div>
        </div>

        <?php Pjax::begin([
            'enablePushState' => false,
            'enableReplaceState' => false,
        ]) ?>

        <?= MembersListWidget::widget() ?>

        <?php Pjax::end() ?>
    </div>
</div>
