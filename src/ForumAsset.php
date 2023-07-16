<?php

namespace rats\forum;

use yii\web\AssetBundle;

class ForumAsset extends AssetBundle
{
    public $sourcePath = '@rats/forum/assets';

    public $js = [];
    public $css = [
        'forum.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}
