<?php

namespace alexantr\ckeditor;

use yii\web\AssetBundle;

/**
 * Class CKEditorAsset
 * @package alexantr\ckeditor
 */
class CKEditorAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'https://cdn.ckeditor.com/4.8.0/standard-all/ckeditor.js',
        'https://cdn.ckeditor.com/4.8.0/standard-all/adapters/jquery.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
