<?php


use rats\forum\models\User;
use yii\helpers\Url;
use rats\forum\ForumModule;

/**
 * @var User $model
 * @var integer $index
 */


// <h3><?= Yii::$app->formatter->asDatetime($model->created_at, 'd. M. Y, HH:mm');

?>

<div class="row py-2 <?= $index % 2 == 0 ? 'bg-light' : 'bg-lighter' ?>">
    <div class="col">
        <h3 class="h5 m-0">
            <a class="link-primary link-underline-opacity-0 link-underline-opacity-100-hover" href="
                <?= Url::to(['/' . ForumModule::getInstance()->id . "/diskuse/profile/" . $model->id ]) ?>"><?= $model->username?></a>
        </h3>
        <div class="d-md-block d-none">
            <span class="small children-mb-0 lines-1"><?= $model->real_name?></span>
        </div>
        <!-- Phone size -->
        <div class="d-md-none d-block small">
            <div class="row">
                <span class="fw-medium col-4"><?= Yii::t('app', 'Posts') ?>: <?=$model->getPosts()->count() ?></span>
                <span class="col-8 fw-bold text-end"> <?= Yii::$app->formatter->asDatetime($model->created_at) ?></span>
            </div>
        </div>
    </div>
    <div class="col-3 d-md-block d-none">
        <div class="row gx-2 h-100 align-items-center">
            <div class="col-6 text-center">
                <span><?=$model->getPosts()->count() ?></span>
            </div>
        </div>
    </div>
    <div class="col-3 d-md-block d-none my-auto last-post" style="font-size: .9rem;">
        <p class="small mb-0 text-end"><span class="fw-bold"> <?= Yii::$app->formatter->asDatetime($model->created_at) ?></p>
    </div>
</div>


