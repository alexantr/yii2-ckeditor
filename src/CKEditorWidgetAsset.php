<?php

namespace alexantr\ckeditor;

use yii\web\AssetBundle;

/**
 * Class CKEditorWidgetAsset
 * @package alexantr\ckeditor
 */
class CKEditorWidgetAsset extends AssetBundle
{
    public $sourcePath = '@alexantr/ckeditor/assets';
    public $js = [
        'ckeditor.widget.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'alexantr\ckeditor\CKEditorAsset',
    ];
}
