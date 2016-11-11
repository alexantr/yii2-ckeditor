<?php

namespace tests;

use alexantr\ckeditor\CKEditorWidgetAsset;
use yii\web\AssetBundle;

class CKEditorWidgetAssetTest extends TestCase
{
    public function testRegister()
    {
        $view = $this->mockView();

        $this->assertEmpty($view->assetBundles);

        CKEditorWidgetAsset::register($view);

        // JqueryAsset, YiiAsset, CKEditorAsset, CKEditorWidgetAsset
        $this->assertEquals(4, count($view->assetBundles));

        $this->assertArrayHasKey('alexantr\\ckeditor\\CKEditorWidgetAsset', $view->assetBundles);
        $this->assertTrue($view->assetBundles['alexantr\\ckeditor\\CKEditorWidgetAsset'] instanceof AssetBundle);

        $out = $view->renderFile('@tests/data/views/layout.php');

        $this->assertContains('/ckeditor.js', $out);
        $this->assertContains('/adapters/jquery.js', $out);
        $this->assertContains('/ckeditor.widget.js', $out);
    }
}
