<?php

use rats\forum\ForumModule;
use rats\forum\models\ThreadSubscription;
use yii\helpers\Html;
use yii\widgets\ListView;

/**
 * @var ThreadSubscription $model
 * @var integer $index
 * @var ListView $widget
 */
?>

<div class="row py-2 <?= $index % 2 == 0 ? 'bg-secondary' : 'bg-secondary-subtle' ?> <?= $index < $widget->dataProvider->getPagination()->pageSize - 1 ? 'border-bottom' : 'rounded-bottom-1' ?>">
    <div class="col-12 col-md">
        <h3 class="h5 m-0">
            <a class="link-primary link-visited link-underline-opacity-0 link-underline-opacity-100-hover text-break" href="<?= $model?->thread->getUrl() ?>">
                <?= $model?->thread?->name ?>
            </a>
        </h3>
    </div>
    <div class="col-auto">
        <?= Html::a(Yii::t('app', '<i class="fa fa-bell-slash"></i> ' . Yii::t('app', 'Unsubscribe thread')), ['/' . ForumModule::getInstance()->id . '/thread-subscription/unsubscribe-authenticated', 'threadId' => $model->fk_thread], [
            'class' => 'btn btn-sm btn-outline-danger',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
    </div>
</div>