<?php

namespace alexantr\ckeditor;

use yii\web\AssetBundle;

class WidgetAsset extends AssetBundle
{
    public $sourcePath = '@alexantr/ckeditor/assets';
    public $js = [
        'widget.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
