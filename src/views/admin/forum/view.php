<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var yii\web\View $this */
/* @var rats\forum\models\Forum $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Forums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="forum-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
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
            ['attribute' => 'fk_category', 'value' => function ($model) {
                return $model->name;
            }],
            'fk_parent',
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
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
