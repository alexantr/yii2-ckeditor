<?php

namespace tests;

use alexantr\ckeditor\CKEditorAsset;
use yii\web\AssetBundle;

class CKEditorAssetTest extends TestCase
{
    public function testRegister()
    {
        $view = $this->mockView();

        $this->assertEmpty($view->assetBundles);

        CKEditorAsset::register($view);

        // JqueryAsset, CKEditorAsset
        $this->assertEquals(2, count($view->assetBundles));

        $this->assertArrayHasKey('alexantr\\ckeditor\\CKEditorAsset', $view->assetBundles);
        $this->assertTrue($view->assetBundles['alexantr\\ckeditor\\CKEditorAsset'] instanceof AssetBundle);

        $out = $view->renderFile('@tests/data/views/layout.php');

        $this->assertContains('/standard-all/ckeditor.js', $out);
        $this->assertContains('/standard-all/adapters/jquery.js', $out);
    }
}
