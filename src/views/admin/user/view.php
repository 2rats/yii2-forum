<?php

use rats\forum\ForumModule;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Forum Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'View user posts'), ['/' . ForumModule::getInstance()->id . '/admin/post/index', 'PostSearch[created_by]' => $model->id], ['class' => 'btn btn-outline-primary']) ?>
        <?php
        $user_role = implode('', array_keys(Yii::$app->authManager->getRolesByUser($model->id)));
        if ((Yii::$app->authManager->checkAccess(Yii::$app->user->identity->id, 'assignAdmin') && $user_role == 'admin') || (Yii::$app->authManager->checkAccess(Yii::$app->user->identity->id, 'assignRole') && $user_role == 'user')) : ?>
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
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->printStatus();
                }
            ],
            [
                'attribute' => 'role',
                'value' => function ($model) {
                    return $model->printRoles();
                },
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>