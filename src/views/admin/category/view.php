<?php

use rats\forum\ForumModule;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var yii\web\View $this */
/* @var rats\forum\models\Category $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'View forums'), ['/' . ForumModule::getInstance()->id . '/admin/forum/index', 'ForumSearch[fk_category]' => $model->id], ['class' => 'btn btn-outline-primary']) ?>
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
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description:ntext',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->printStatus();
                }
            ],
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

            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
