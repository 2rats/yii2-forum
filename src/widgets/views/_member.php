<?php


use rats\forum\models\User;
use yii\helpers\Url;
use rats\forum\ForumModule;

/**
 * @var User $model
 * @var integer $index
 */
?>

<div class="row py-2 <?= $index % 2 == 0 ? 'bg-secondary-subtle' : 'bg-secondary' ?>">
    <div class="col">
        <h3 class="h5 m-0">
            <a class="link-primary link-underline-opacity-0 link-underline-opacity-100-hover" href="
                <?= Url::to(['/' . ForumModule::getInstance()->id . "/profile/" . $model->id]) ?>"><?= $model->getDisplayName() ?></a>
        </h3>
        <!-- Phone size -->
        <div class="d-md-none d-block small">
            <div class="row">
                <span class="fw-medium col-4"><?php echo Yii::t('app', 'Posts') ?>: <?=  Yii::$app->formatter->asInteger($model->getPosts()->count()) ?></span>
                <span class="col-8 fw-bold text-end"> <?php echo $model->getCreatedAtString() ?></span>
            </div>
        </div>
    </div>
    <div class="col-3 d-md-block d-none">
        <div class="row gx-2 h-100 align-items-center">
            <div class="col-6 text-center">
                <span><?= Yii::$app->formatter->asInteger($model->getPosts()->count()) ?></span>
            </div>
        </div>
    </div>
    <div class="col-3 d-md-block d-none my-auto last-post" style="font-size: .9rem;">
        <p class="small mb-0 text-end"><span class="fw-bold"> <?= $model->getCreatedAtString() ?></p>
    </div>
</div>
