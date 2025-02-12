<?php

namespace rats\forum;

use yii\web\AssetBundle;

class ForumAsset extends AssetBundle
{
    public $sourcePath = '@rats/forum/assets';

    public $js = [
        'postScroll.js',
        'create_post.js',
        'fancybox.umd.js',
        'forum-fancybox.js',
        'tinymce/cs.js',
    ];
    public $css = [
        'forum.css',
        'fancybox.css',
        'forum-fancybox.css',
        'tinymce/tinymce.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}
