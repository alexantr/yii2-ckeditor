<?php

namespace tests;

use alexantr\ckeditor\CKEditor;
use tests\data\models\Post;
use Yii;

/**
 * Tests for CKEditor widget
 */
class CKEditorTest extends TestCase
{
    public function testRenderWithModel()
    {
        $model = new Post();
        $out = CKEditor::widget([
            'model' => $model,
            'attribute' => 'message',
        ]);
        $expected = '<textarea id="post-message" name="Post[message]"></textarea>';

        $this->assertEqualsWithoutLE($expected, $out);
    }

    public function testRenderWithNameAndValue()
    {
        $out = CKEditor::widget([
            'id' => 'test',
            'name' => 'test-editor-name',
            'value' => 'test-editor-value',
        ]);
        $expected = '<textarea id="test" name="test-editor-name">test-editor-value</textarea>';

        $this->assertEqualsWithoutLE($expected, $out);
    }

    public function testRenderRegisterOnChangeHandler()
    {
        $model = new Post();
        $widget = CKEditor::widget([
            'model' => $model,
            'attribute' => 'message',
        ]);

        $out = Yii::$app->view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $test = 'alexantr.ckEditorWidget.registerOnChangeHandler(\'post-message\');';
        $this->assertContains($test, $out);
    }

    public function testRenderRegisterCsrfImageUploadHandler()
    {
        $model = new Post();
        $widget = CKEditor::widget([
            'model' => $model,
            'attribute' => 'message',
            'clientOptions' => [
                'filebrowserUploadUrl' => '/',
            ],
        ]);

        $out = Yii::$app->view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $test = 'alexantr.ckEditorWidget.registerCsrfImageUploadHandler();';
        $this->assertContains($test, $out);
    }

    public function testRenderWithPresetName()
    {
        $this->mockWebApplication([
            'params' => [
                'ckeditor.testConfig' => [
                    'stylesSet' => false,
                ],
            ]
        ]);

        $model = new Post();
        $widget = CKEditor::widget([
            'model' => $model,
            'attribute' => 'message',
            'presetName' => 'ckeditor.testConfig',
        ]);

        $out = Yii::$app->view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $test = 'CKEDITOR.replace(\'post-message\', {"stylesSet":false});';
        $this->assertContains($test, $out);
    }

    public function testRenderWithContentsCssAlias()
    {
        Yii::setAlias('@web', '/test');

        $model = new Post();
        $widget = CKEditor::widget([
            'model' => $model,
            'attribute' => 'message',
            'clientOptions' => [
                'contentsCss' => '@web/css/style.css',
            ],
        ]);

        $out = Yii::$app->view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $test = 'CKEDITOR.replace(\'post-message\', {"contentsCss":"/test/css/style.css"});';
        $this->assertContains($test, $out);
    }

    public function testRenderWithContentsCssAliasesArray()
    {
        Yii::setAlias('@web', '/test');

        $model = new Post();
        $widget = CKEditor::widget([
            'model' => $model,
            'attribute' => 'message',
            'clientOptions' => [
                'contentsCss' => ['@web/css/style1.css', '/test/css/style2.css'],
            ],
        ]);

        $out = Yii::$app->view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $test = 'CKEDITOR.replace(\'post-message\', {"contentsCss":["/test/css/style1.css","/test/css/style2.css"]});';
        $this->assertContains($test, $out);
    }

    public function testRenderWithCustomConfigAlias()
    {
        Yii::setAlias('@web', '/test');

        $model = new Post();
        $widget = CKEditor::widget([
            'model' => $model,
            'attribute' => 'message',
            'clientOptions' => [
                'customConfig' => '@web/js/custom.js',
            ],
        ]);

        $out = Yii::$app->view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $test = 'CKEDITOR.replace(\'post-message\', {"customConfig":"/test/js/custom.js"});';
        $this->assertContains($test, $out);
    }

    public function testRenderWithStylesSetAlias()
    {
        Yii::setAlias('@web', '/test');

        $model = new Post();
        $widget = CKEditor::widget([
            'model' => $model,
            'attribute' => 'message',
            'clientOptions' => [
                'stylesSet' => 'testStyle:@web/js/styles.js',
            ],
        ]);

        $out = Yii::$app->view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $test = 'CKEDITOR.replace(\'post-message\', {"stylesSet":"testStyle:/test/js/styles.js"});';
        $this->assertContains($test, $out);
    }
}
