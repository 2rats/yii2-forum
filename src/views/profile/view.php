<?php

/**
 * @var yii\web\View $this
 * @var rats\forum\models\User $user
 * @var yii\data\ActiveDataProvider $postsDataProvider
 */

use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = $user->getDisplayName();

if (Yii::$app->user->identity->id == $user->id) {
    $this->params['breadcrumbs'][] = Yii::t('app', 'Profile');
} else {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profile'), 'url' => ['view', 'id' => Yii::$app->user->identity->id]];
    $this->params['breadcrumbs'][] = $user->getDisplayName();
}
?>
<!-- Github markdown styles -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/github-markdown-css/5.2.0/github-markdown-light.min.css" integrity="sha512-bm684OXnsiNuQSyrxuuwo4PHqr3OzxPpXyhT66DA/fhl73e1JmBxRKGnO/nRwWvOZxJLRCmNH7FII+Yn1JNPmg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="container-lg">
    <?php echo Breadcrumbs::widget(
        [
        'links' => $this->params['breadcrumbs'],
        'homeLink' => [
            'label' => Yii::$app->name,
            'url' => Url::to(['/diskuse']),
        ],
        ]
    ); ?>

    <div class="row justify-content-center my-3 post-container">
        <div class="col-11">
            <div class="rounded-top d-flex flex-column flex-md-row profile-banner" style="height: unset; min-height: 200px; background-image: linear-gradient(0deg, rgba(0, 143, 171, 1) 0%, rgba(0, 143, 171,.7) 25%, rgba(0, 143, 171, .1) 95%, rgba(255,255,255,0) 100%);">
                <?php if ($image = $user->image) : ?>
                    <div class="ms-4 mt-5 d-flex flex-column" style="width: 150px;">
                        <img src="<?php echo $image->getFileUrl() ?>" alt="<?php echo Yii::t('app', 'Profile picture') ?>" class="img-fluid img-thumbnail mt-4 mb-2">
                    </div>
                <?php endif; ?>
                <div class="ms-4 mt-auto mt-md-auto mt-2 text-white">
                    <h1 class="h5"><?php echo $user->getDisplayName() ?></h1>
                    <?php if (Yii::$app->user->can('admin') || Yii::$app->user->can('forum-moderator')) : ?>
                        <p class="small"><?php echo $user->email ?></p>
                    <?php endif; ?>
                </div>
                <div class="ms-4 ms-md-auto mt-auto me-3 mb-3">
                    <p class="mb-2 text-white fw-bold small"><?php echo Yii::t('app', 'Role') ?></p>
                    <?php foreach ($user->roles as $role): ?>
                        <small class="w-fit badge text-bg-primary m-1 px-2 py-1 shadow-sm rounded-1 ms-2 me-0 m-1 px-2 py-1 "><?php echo ucfirst($role->name) ?></small>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="shadow-sm py-4 px-md-4 text-bg-secondary border rounded-bottom">
                <div class="d-flex justify-content-center text-center py-1 flex-wrap">
                    <?php if (Yii::$app->user->identity->id == $user->id) : ?>
                        <?php echo Html::a(Yii::t('app', 'Edit profile'), ['update', 'id' => $user->id], ['class' => 'w-100 w-md-auto btn btn-primary ms-4 me-4 mb-4 my-md-auto ms-md-0 me-md-auto', 'style' => 'width: 150px;']) ?>
                    <?php endif; ?>
                    <div class="px-2 px-md-3 border-end">
                        <p class="mb-1 h5"><?php echo Yii::$app->formatter->asInteger($user->getPosts()->count()) ?></p>
                        <p class="small text-secondary mb-0"><?php echo Yii::t('app', 'Posts') ?></p>
                    </div>
                    <div class="px-2 px-md-3 border-end">
                        <p class="mb-1 h5"><?php echo Yii::$app->formatter->asInteger($user->getThreads()->count()) ?></p>
                        <p class="small text-secondary mb-0"><?php echo Yii::t('app', 'Threads') ?></p>
                    </div>
                    <div class="px-2 px-md-3">
                        <p class="mb-1 h5"><?php echo Yii::$app->formatter->asDate($user->created_at) ?></p>
                        <p class="small text-secondary mb-0"><?php echo Yii::t('app', 'Joined') ?></p>
                    </div>
                </div>
            </div>
            <?php if ($postsDataProvider->getTotalCount() > 0) : ?>

                <div class="mt-4 mx-3">
                    <div class="container-lg border rounded-1 text-secondary bg-secondary">
                        <div class="d-flex gap-2 justify-content-between py-2 rounded-top-1">
                            <div class="py-2">
                                <h2 class="h3 text-tertiary fw-bold mb-0 text-decoration-underline"><?php echo Yii::t('app', 'Posts') ?></h2>
                            </div>
                        </div>

                        <div class="row py-2 border-bottom border-top text-bg-primary">
                            <div class="col-2 border-md-end d-md-block d-none">
                                <span class="fw-bold"><?php echo Yii::t('app', 'Forum') ?></span>
                            </div>
                            <div class="col border-md-end">
                                <span class="fw-bold"><?php echo Yii::t('app', 'Thread') ?></span>
                            </div>
                            <div class="col-6 d-md-block d-none">
                                <span class="fw-bold"><?php echo Yii::t('app', 'Post') ?></span>
                            </div>
                        </div>

                        <?php Pjax::begin(
                            [
                            'scrollTo' => true,
                            'linkSelector' => '.pagination a',
                            ]
                        ) ?>

                        <?php echo ListView::widget(
                            [
                            'dataProvider' => $postsDataProvider,
                            'itemView' => '@rats/forum/widgets/views/_post',
                            'layout' => "{items}",
                            'options' => ['class' => 'post-list'],
                            ]
                        ) ?>

                        <?php Pjax::end() ?>

                    </div>

                    <?php echo LinkPager::widget(
                        [
                        'pagination' => $postsDataProvider->pagination,
                        'options' => [
                            'class' => 'd-flex justify-content-center mt-3',
                        ],
                        ]
                    ) ?>

                </div>

            <?php endif; ?>
        </div>
    </div>
</div>
