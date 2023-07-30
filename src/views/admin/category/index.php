<?php

use rats\forum\models\Category;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use rats\forum\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var yii\web\View $this */
/* @var rats\forum\models\search\CategorySearch $searchModel */
/* @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Category'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]);
    ?>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'responsiveWrap' => false,
        'columns' => [
            'id',
            [
                'attribute' => 'name',
                'headerOptions' => ['style' => 'min-width:200px'],
            ],
            [
                'attribute' => 'description',
                'headerOptions' => ['style' => 'min-width:200px'],
            ],
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    return $model->createdBy->username;
                },
                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['prompt' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                    'data' => ArrayHelper::map(User::find()->all(), 'id', 'username')
                ],
                'headerOptions' => ['style' => 'min-width:200px'],
            ],
            [
                'attribute' => 'updated_by',
                'value' => function ($model) {
                    return $model->updatedBy->username;
                },
                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['prompt' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                    'data' => ArrayHelper::map(User::find()->all(), 'id', 'username')
                ],
                'headerOptions' => ['style' => 'min-width:200px'],
            ],

            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Category $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'headerOptions' => ['style' => 'min-width:75px'],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>