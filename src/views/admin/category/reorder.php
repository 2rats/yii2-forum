<?php

/** @var yii\web\View $this */
/* @var Forum[] $forums */
/* @var bool $subforum */

use rats\forum\models\Forum;
use rats\forum\ReorderAsset;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Reorder');
$this->params['breadcrumbs'][] = $this->title;

ReorderAsset::register($this);

?>

<div class="category-reorder">
    <h1 class='mb-3'><?= Html::encode($this->title) ?></h1>
    <div class="alert alert-success" role="alert" style="display: none;">
        <?= Yii::t('app', 'The reordering has been successfully saved.') ?>
    </div>
    <div class="alert alert-danger" role="alert" style="display: none;">
        <?= Yii::t('app', 'An error has occurred.') ?>
    </div>
    <div class="category-container text-secondary parent-reorder">
        <?php if (empty($categories)) : ?>
            <div class="no-results row py-2 bg-secondary-subtle rounded">
                <div class="col-12 text-center"><?= Yii::t('app', 'No forum categories') ?></div>
            </div>
        <?php else : ?>
            <button class='btn btn-lg btn-dark' id='save'> <?= Yii::t('app', 'Save') ?></button>
        <?php endif; ?>
        <?php foreach ($categories as $index => $category) : ?>
            <div class="parent row justify-content-center mb-0 py-2 border-bottom" data-id="<?= $category->id ?>">
                <div class="col-11">
                    <h3 class="mb-0">
                        <?= $category->name ?>
                        <svg class='show-button' xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <svg style='display: none;' class='hide-button' xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </h3>
                </div>
                <?= $this->render('reorder-forum', [
                    'forums' => $category->getForums()->active()->topLevel()->all(),
                    'subforum' => false
                ]); ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
