<?php

use rats\forum\models\Post;
use rats\forum\components\StatusColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use rats\forum\models\Thread;
use rats\forum\models\User;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var rats\forum\models\search\PostSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Posts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (Yii::$app->user->can('forum-admin')): ?>
            <?= Html::a(Yii::t('app', 'Create Post'), ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
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
                'attribute' => 'fk_thread',
                'value' => 'thread.name',
                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'hideSearch' => true,
                    'options' => ['prompt' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'ajax' => [
                            'url' => \yii\helpers\Url::to(['thread-list']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(model) { return model.text; }'),
                        'templateSelection' => new JsExpression('function (model) { return model.text; }'),
                    ],
                    // 'data' => \yii\helpers\ArrayHelper::map(Thread::find()->all(), 'id', 'name')
                ],
                'headerOptions' => ['style' => 'min-width:200px'],
            ],
            [
                'attribute' => 'content',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->printContent(true);
                },
                'headerOptions' => ['style' => 'min-width:300px'],
            ],
            [
                'class' => StatusColumn::class,
            ],
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    return $model->createdBy->getDisplayName();
                },
                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['prompt' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'ajax' => [
                            'url' => \yii\helpers\Url::to(['user-list']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(model) { return model.text; }'),
                        'templateSelection' => new JsExpression('function (model) { return model.text; }'),
                    ],
                    // 'data' => ArrayHelper::map(User::find()->all(), 'id', 'username')
                ],
                'headerOptions' => ['style' => 'min-width:200px'],
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Post $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'headerOptions' => ['style' => 'min-width:75px'],
                'visibleButtons' => [
                    'update' => function ($model) {
                        return Yii::$app->user->can('forum-editThread', ['thread' => $model->thread]);
                    },
                    'delete' => function ($model) {
                        return Yii::$app->user->can('forum-editThread', ['thread' => $model->thread]);
                    },
                ]
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>