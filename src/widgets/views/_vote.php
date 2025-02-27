<?php

use yii\widgets\ListView;

/**
 * @var Vote $model
 * @var integer $index
 * @var ListView $widget
 */
?>

<li class="list-group-item d-flex align-items-center bg-secondary border-0 border-bottom text-tertiary p-1">
    <div
        style="height: 2.5rem; width: 2.5rem;"
        class="me-2">
        <?php if ($image = $model->user->getProfileImage()): ?>
            <img
                style="width: 100%; height: 100%; object-fit: cover; overflow-clip-margin: unset;"
                class="w-100 h-100 rounded-circle"
                src="<?= $image->getFileUrl() ?>"
                alt="<?= Yii::t('app', 'Profile picture') ?>">
        <?php endif; ?>
    </div>

    <a href="<?= $model->user->getUrl() ?>" class="lines-1 w-75 text-secondary"><?= htmlspecialchars($model->user->getDisplayName()) ?></a>
</li>