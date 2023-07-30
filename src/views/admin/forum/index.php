<?php

use rats\forum\models\Forum;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use rats\forum\models\Category;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var yii\web\View $this */
/* @var rats\forum\models\ForumSearch $searchModel */
/* @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Forums');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forum-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Forum'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'responsiveWrap' => false,
        'columns' => [
            'id',
            [
                'label' => Yii::t('app', 'Category'),
                'attribute' => 'fk_category',
                'value' => function ($model) {
                    return $model->category->name;
                },
                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'hideSearch' => true,
                    'options' => ['prompt' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                    'data' => ArrayHelper::map(Category::find()->all(), 'id', 'name')
                ],
                'headerOptions' => ['style' => 'min-width:200px'],
            ],
            [
                'attribute' => 'name',
                'headerOptions' => ['style' => 'min-width:200px'],
            ],
            // 'description:ntext',
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
                        Forum::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
                        Forum::STATUS_ACTIVE_UNLOCKED => Yii::t('app', 'Unlocked'),
                        Forum::STATUS_ACTIVE_LOCKED => Yii::t('app', 'Locked'),
                    ],
                ],
                'headerOptions' => ['style' => 'min-width:150px'],
            ],

            'threads',
            'posts',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Forum $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'headerOptions' => ['style' => 'min-width:75px'],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>