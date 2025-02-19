<?php

namespace rats\forum;

use yii\web\AssetBundle;

class LoadingOverlayAsset extends AssetBundle
{
    public $sourcePath = "@npm/gasparesganga-jquery-loading-overlay/dist";

    /**
     * @var array<string> The JS files to include
     */
    public $js = [
        'loadingoverlay.min.js',
        '/js/loadingoverlay/custom.js',
    ];

    /**
     * @var array<string> Assets that this asset bundle depends on
     */
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
