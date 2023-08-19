<?php

namespace rats\forum;

use yii\web\AssetBundle;

class ReorderAsset extends AssetBundle
{
    public $sourcePath = '@rats/forum/assets';

    public $js = [
        'Sortable.min.js',
        'jquery-sortable.js',
        'reorder.js',
    ];
    public $css = ['reorder.css'];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}
