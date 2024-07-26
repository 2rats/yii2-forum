<?php

use rats\forum\ForumModule;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;


/* @var yii\web\View $this */
/* @var rats\forum\models\Forum $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Forums'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="forum-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'View threads'), ['/' . ForumModule::getInstance()->id . '/admin/thread/index', 'ThreadSearch[fk_forum]' => $model->id], ['class' => 'btn btn-outline-primary']) ?>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'options' => ['class' => 'table table-striped table-bordered detail-view'],
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'fk_category',
                'value' => Html::a($model->category->name, Url::to([
                    '/' . ForumModule::getInstance()->id . '/admin/category/view', 'id' => $model->category->id
                ])),
                'format' => 'raw',
            ],
            [
                'attribute' => 'fk_parent',
                'value' => $model->parent ? Html::a($model->parent->name, Url::to([
                    '/' . ForumModule::getInstance()->id . '/admin/forum/view', 'id' => $model->parent->id
                ])) : null,
                'format' => 'raw',
            ],
            'name',
            'description:ntext',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->printStatus();
                }
            ],
            'threads',
            'posts',
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    return $model->createdBy->username;
                }
            ],
            [
                'attribute' => 'updated_by',
                'value' => function ($model) {
                    return $model->updatedBy->username;
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <h2><?= Yii::t('app', 'Moderators') ?></h2>

    <p>
        <?= Html::a(Yii::t('app', 'Add moderator'), ['admin/forum-moderator/create', 'fk_forum' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $model->getForumModeratorDataProvider(),
        'columns' => [
            'user.id',
            'user.username',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'delete') {
                        return ['admin/forum-moderator/delete', 'id' => $model->id];
                    }
                }
            ]
        ],
    ])
    ?>


</div>
