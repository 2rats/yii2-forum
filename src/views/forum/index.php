<?php

/** @var yii\web\View $this */
/** @var Forum[] $forums */
/** @var bool $subforum */

use rats\forum\widgets\ForumListWidget;
use yii\widgets\Pjax;

?>


<div class="row justify-content-center mb-4">
    <div class="col-11 forum-container border rounded-1 text-secondary">
        <div class="forum-header row py-2 border-bottom bg-lighter fw-bold rounded-top-1">
            <div class="col-12 col-md-9 border-end">
                <span class="mx-2"><?= $subforum ? Yii::t('app', 'Subforum') : Yii::t('app', 'Forum') ?></span>
            </div>
            <div class="col-3 d-md-block d-none">
                <span class="mx-2"><?= Yii::t('app', 'Last post') ?></span>
            </div>
        </div>
        <?php Pjax::begin([
            'enablePushState' => false,
            'enableReplaceState' => false,
        ]) ?>

        <?= ForumListWidget::widget([
            'categoryId' => $categoryId,
        ]) ?>

        <?php Pjax::end() ?>
    </div>
</div>