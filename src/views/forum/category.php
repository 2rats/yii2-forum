<?php

/** @var yii\web\View $this */
/** @var Forum[] $forums */
/** @var bool $subforum */

use rats\forum\ForumModule;
use rats\forum\models\Forum;
use yii\helpers\Url;

?>
<div class="category-container text-secondary">
    <?php if (sizeof($categories) == 0) : ?>
        <div class="no-results row py-2 bg-lighter rounded">
            <div class="col-12 text-center"><?= Yii::t('app', 'No forum categories') ?></div>
        </div>
    <?php endif; ?>
    <?php foreach ($categories as $index => $category) : ?>
        <div class="row justify-content-center mb-0">
            <div class="col-11">
            <h3 class="mb-0"><?= $category->name ?></h3>
            <p class="small mb-2"><?= $category->description ?></p>
        </div>
        </div>
        <?= $this->render('index', [
            'forums' => $category->getForums()->andWhere([
                'fk_parent' => null,
                'status' => [
                    Forum::STATUS_ACTIVE_LOCKED,
                    Forum::STATUS_ACTIVE_UNLOCKED
                ]
            ])->all(),
            'subforum' => false
        ]); ?>
    <?php endforeach; ?>
</div>