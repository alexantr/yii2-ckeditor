<?php

namespace alexantr\ckeditor;

use yii\web\AssetBundle;

/**
 * Class CKEditorPresetAsset
 * @package alexantr\ckeditor
 */
class CKEditorPresetAsset extends AssetBundle
{
    public $sourcePath = '@alexantr/ckeditor/assets/preset';
    public $depends = [
        'alexantr\ckeditor\CKEditorWidgetAsset',
    ];
}
