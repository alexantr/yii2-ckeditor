<?php

namespace tests;

use alexantr\ckeditor\CKEditorAsset;
use yii\web\AssetBundle;
use Yii;

/**
 * Tests for CKEditorAsset
 */
class CKEditorAssetTest extends TestCase
{
    public function testRegister()
    {
        $view = Yii::$app->view;

        $this->assertEmpty($view->assetBundles);
        CKEditorAsset::register($view);
        $this->assertEquals(2, count($view->assetBundles));

        $this->assertArrayHasKey('yii\\web\\JqueryAsset', $view->assetBundles);
        $this->assertTrue($view->assetBundles['alexantr\\ckeditor\\CKEditorAsset'] instanceof AssetBundle);

        $out = $view->renderFile('@tests/data/views/layout.php', ['content' => '']);

        $this->assertContains('/ckeditor.js', $out);
        $this->assertContains('/adapters/jquery.js', $out);
    }
}
