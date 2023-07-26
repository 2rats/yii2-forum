<?php

use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var rats\forum\models\Forum $model */

$this->title = 'Create Forum';
$this->params['breadcrumbs'][] = ['label' => 'Forums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forum-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render(
        '_form',
        ['model' => $model]
    ) ?>

</div>
