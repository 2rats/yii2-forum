<?php

use rats\forum\models\Thread;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use rats\forum\models\Forum;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var rats\forum\models\search\ThreadSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Threads');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thread-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Thread'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'responsiveWrap' => false,
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => ['style' => 'min-width:100px'],
            ],
            [
                'attribute' => 'fk_forum',
                'value' => 'forum.name',
                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'hideSearch' => true,
                    'options' => ['prompt' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                    'data' => \yii\helpers\ArrayHelper::map(Forum::find()->all(), 'id', 'name')
                ],
                'headerOptions' => ['style' => 'min-width:250px'],
            ],
            [
                'attribute' => 'name',
                'headerOptions' => ['style' => 'min-width:200px'],
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->printStatus();
                },
                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'hideSearch' => true,
                    'options' => ['prompt' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                    'data' => [
                        Thread::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
                        Thread::STATUS_ACTIVE_UNLOCKED => Yii::t('app', 'Unlocked'),
                        Thread::STATUS_ACTIVE_LOCKED => Yii::t('app', 'Locked'),
                    ],
                ],
                'headerOptions' => ['style' => 'min-width:150px'],
            ],
            'posts',
            'views',
            [
                'attribute' => 'pinned',
                'class' => 'kartik\grid\BooleanColumn',
                'trueIcon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
                'falseIcon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>',
                'value' => function ($model) {
                    return $model->pinned == Thread::PINNED_TRUE;
                },
                'trueLabel' => Yii::t('app', 'Yes'),
                'falseLabel' => Yii::t('app', 'No'),
                'headerOptions' => ['style' => 'min-width:150px'],
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Thread $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'headerOptions' => ['style' => 'min-width:75px'],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>