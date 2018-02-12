<?php

namespace tests;

use alexantr\ckeditor\WidgetAsset;
use yii\web\AssetBundle;

class WidgetAssetTest extends TestCase
{
    public function testRegister()
    {
        $view = $this->mockView();

        $this->assertEmpty($view->assetBundles);

        WidgetAsset::register($view);

        // JqueryAsset, YiiAsset, CKEditorWidgetAsset
        $this->assertEquals(3, count($view->assetBundles));

        $this->assertArrayHasKey('alexantr\\ckeditor\\WidgetAsset', $view->assetBundles);
        $this->assertTrue($view->assetBundles['alexantr\\ckeditor\\WidgetAsset'] instanceof AssetBundle);

        $out = $view->renderFile('@tests/data/views/layout.php');

        $this->assertContains('/widget.js', $out);
    }
}
