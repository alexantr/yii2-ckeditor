<?php

namespace alexantr\ckeditor;

use yii\web\AssetBundle;

/**
 * Class CKEditorAsset
 * @package alexantr\ckeditor
 */
class CKEditorAsset extends AssetBundle
{
    public $sourcePath = '@vendor/ckeditor/ckeditor';
    public $js = [
        'ckeditor.js',
        'adapters/jquery.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public $publishOptions = [
        'except' => ['samples/'],
    ];
}
