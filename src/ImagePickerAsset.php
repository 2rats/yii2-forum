<?php

namespace rats\forum;

use yii\web\AssetBundle;

class ImagePickerAsset extends AssetBundle
{
    public $sourcePath = '@rats/forum/assets';

    public $js = ['image-picker.js'];

    public $css = ['image-picker.css'];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}
