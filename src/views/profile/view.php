<?php

/** @var yii\web\View $this */

use yii\widgets\DetailView;

$this->title = $user->username;
$this->params['breadcrumbs'][] = Yii::t('app', 'Profile');
$this->params['breadcrumbs'][] = $user->username;
?>
<!-- Github markdown styles -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/github-markdown-css/5.2.0/github-markdown-light.min.css" integrity="sha512-bm684OXnsiNuQSyrxuuwo4PHqr3OzxPpXyhT66DA/fhl73e1JmBxRKGnO/nRwWvOZxJLRCmNH7FII+Yn1JNPmg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="row justify-content-center my-3 post-container">
    <div class="col-11">
        <div class="rounded-top d-flex flex-column flex-md-row profile-banner" style="background-image: linear-gradient(0deg, rgba(0,0,0,.6) 0%, rgba(0,0,0,.6) 13%, rgba(255,255,255,1) 95%, rgba(255,255,255,1) 100%);">
            <!-- <div class="ms-4 mt-5 d-flex flex-column" style="width: 150px;">
                <img src="" alt="Profile picture" class="img-thumbnail mt-4 mb-2">
            </div> -->
            <div class="ms-4 mt-md-auto mt-2 text-white">
                <h5><?= $user->username ?></h5>
                <p class="small"><?= $user->email ?></p>
            </div>
            <div class="ms-4 ms-md-auto mt-auto me-3 mb-3">
                <p class="mb-2 text-white fw-bold small"><?= Yii::t('app', 'Role') ?></p>
                <?php foreach ($user->roles as $role) : ?>
                    <small class="w-fit bg-lighter shadow-sm rounded-1 ms-2 me-0 m-1 px-2 py-1 "><?= ucfirst($role->name) ?></small>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="shadow-sm p-4 text-black" style="background-color: #f8f9fa;">
            <div class="d-flex justify-content-md-end justify-content-center text-center py-1">
                <?php if (Yii::$app->user->identity->id == $user->id) : ?>
                    <button type="button" class="btn btn-outline-dark me-auto" style="width: 150px;">
                        <?= Yii::t('app', 'Edit profile') ?>
                    </button>
                <?php endif; ?>
                <div class="px-3 border-end">
                    <p class="mb-1 h5"><?= $user->getPosts()->count() ?></p>
                    <p class="small text-muted mb-0"><?= Yii::t('app', 'Posts') ?></p>
                </div>
                <div class="px-3 border-end">
                    <p class="mb-1 h5"><?= $user->getThreads()->count() ?></p>
                    <p class="small text-muted mb-0"><?= Yii::t('app', 'Threads') ?></p>
                </div>
                <div class="px-3">
                    <p class="mb-1 h5"><?= Yii::$app->formatter->asDate($user->created_at) ?></p>
                    <p class="small text-muted mb-0"><?= Yii::t('app', 'Joined') ?></p>
                </div>
            </div>
        </div>
        <div class="card-body p-4 text-black">
            <?= DetailView::widget([
                'model' => $user,
                'attributes' => [
                    'real_name',
                    'username',
                    'email:email',
                ],
                'options' => [
                    'class' => 'table'
                ]
            ]) ?>
        </div>
    </div>
</div>