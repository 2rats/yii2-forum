<?php

use rats\forum\ForumModule;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var rats\forum\models\Thread $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Threads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="thread-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'View posts'), ['/' . ForumModule::getInstance()->id . '/admin/post/index', 'PostSearch[fk_thread]' => $model->id], ['class' => 'btn btn-outline-primary']) ?>
        <?php if (Yii::$app->user->can('forum-editThread', ['thread' => $model])): ?>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'forum',
                'label' => Yii::t('app', 'Forum'),
                'value' => Html::a($model->forum->name, Url::to([
                    '/' . ForumModule::getInstance()->id . '/admin/forum/view',
                    'id' => $model->forum->id
                ])),
                'format' => 'raw',
            ],
            'name',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->printStatus();
                }
            ],
            'posts',
            'views',
            'pinned:boolean',
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    return $model->createdBy->getDisplayName();
                }
            ],
            [
                'attribute' => 'updated_by',
                'value' => function ($model) {
                    return $model->updatedBy->getDisplayName();
                }
            ],

            'seo_title',
            'seo_description',
            'seo_keywords',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>