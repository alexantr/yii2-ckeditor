<?php

namespace tests;

use alexantr\ckeditor\CKEditorWidgetAsset;
use yii\web\AssetBundle;
use Yii;

/**
 * Tests for CKEditorWidgetAsset
 */
class CKEditorWidgetAssetTest extends TestCase
{
    public function testRegister()
    {
        $view = Yii::$app->view;

        $this->assertEmpty($view->assetBundles);
        CKEditorWidgetAsset::register($view);
        $this->assertEquals(4, count($view->assetBundles));

        $this->assertArrayHasKey('yii\\web\\JqueryAsset', $view->assetBundles);
        $this->assertTrue($view->assetBundles['alexantr\\ckeditor\\CKEditorWidgetAsset'] instanceof AssetBundle);

        $out = $view->renderFile('@tests/data/views/layout.php', ['content' => '']);

        $this->assertContains('/ckeditor.js', $out);
        $this->assertContains('/adapters/jquery.js', $out);
        $this->assertContains('/ckeditor.widget.js', $out);
    }
}
