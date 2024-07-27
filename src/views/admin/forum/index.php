<?php

use kartik\grid\GridView;
use rats\forum\models\Category;
use rats\forum\models\Forum;
use rats\forum\components\StatusColumn;
use yii\grid\ActionColumn;
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
        <?= Html::a(Yii::t('app', 'Reorder'), ['admin/category/reorder'], ['class' => 'btn btn-primary']) ?>
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
                'class' => StatusColumn::class,
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
