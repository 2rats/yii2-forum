<?php

use kartik\grid\GridView;
use rats\forum\models\User;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var yii\web\View $this */
/* @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Forum Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'responsiveWrap' => false,
        'columns' => [
            'id',
            'username',
            [
                'attribute' => 'email',
                'format' => 'html',
                'value' => function ($model) {
                    return Yii::$app->formatter->asEmail($model->email);
                },
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
                        User::STATUS_ACTIVE => Yii::t('app', 'Active'),
                        User::STATUS_DELETED => Yii::t('app', 'Deleted'),
                        User::STATUS_MUTED => Yii::t('app', 'Muted'),
                    ],
                ],
                'headerOptions' => ['style' => 'min-width:150px'],
            ],
            [
                'label' => 'Role',
                'attribute' => 'role',
                'value' => function ($model) {
                    return $model->printRoles();
                },
                'headerOptions' => ['style' => 'min-width:150px'],
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'headerOptions' => ['style' => 'min-width:75px'],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
