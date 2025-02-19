<?php

/** @var yii\web\View $this */
/** @var Forum[] $forums */
/** @var bool $subforum */
?>

<div class="category-container text-secondary">
    <?php if (empty($categories)) : ?>
        <div class="no-results row py-2 bg-secondary-subtle rounded">
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
            'categoryId' => $category->id,
            'subforum' => false
        ]); ?>
    <?php endforeach; ?>
</div>
