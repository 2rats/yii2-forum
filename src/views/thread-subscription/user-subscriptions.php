<?php

use rats\forum\ForumModule;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'My Thread Subscriptions');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pe-lg-4">
    <h1 class="text-tertiary"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Unsubscribe from all threads'), ['/' . ForumModule::getInstance()->id . '/thread-subscription/unsubscribe-all'], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to unsubscribe from all threads?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>


    <div class="mt-4 mx-3">

        <?php Pjax::begin([
            'scrollTo' => true,
            'linkSelector' => '.pagination a',
        ]) ?>

        <div class="container-lg border rounded-1 text-secondary bg-secondary">
            <div class="d-flex gap-2 justify-content-between py-2 rounded-top-1">
                <div class="py-2">
                    <h2 class="h3 text-tertiary fw-bold mb-0 text-decoration-underline"><?= Yii::t('app', 'Threads') ?></h2>
                </div>
            </div>

            <div class="row py-2 border-bottom border-top text-bg-primary">
                <div class="col-12 col-md">
                    <span class="fw-bold"><?= Yii::t('app', 'Thread') ?></span>
                </div>
            </div>

            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '@rats/forum/widgets/views/_threadSubscription',
                'layout' => "{items}",
                'options' => ['class' => 'thread-subscription-list'],
                'emptyTextOptions' => [
                    'class' => 'text-center text-secondary my-2',
                ]
            ]) ?>

        </div>

        <?= LinkPager::widget([
            'pagination' => $dataProvider->pagination,
            'options' => [
                'class' => 'd-flex justify-content-center mt-3',
            ],
        ]) ?>

        <?php Pjax::end() ?>

    </div>
</div>