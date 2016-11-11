<?php

namespace tests;

use alexantr\ckeditor\CKEditor;
use tests\data\models\Post;
use Yii;

class CKEditorTest extends TestCase
{
    public function testRenderWithModel()
    {
        $view = $this->mockView();

        $model = new Post();
        $out = CKEditor::widget([
            'view' => $view,
            'model' => $model,
            'attribute' => 'message',
        ]);
        $expected = '<textarea id="post-message" name="Post[message]"></textarea>';

        $this->assertEqualsWithoutLE($expected, $out);
    }

    public function testRenderWithNameAndValue()
    {
        $view = $this->mockView();

        $out = CKEditor::widget([
            'view' => $view,
            'id' => 'test',
            'name' => 'test-editor-name',
            'value' => 'test-editor-value',
        ]);
        $expected = '<textarea id="test" name="test-editor-name">test-editor-value</textarea>';

        $this->assertEqualsWithoutLE($expected, $out);
    }

    public function testRegisterHandlers()
    {
        $view = $this->mockView();

        $model = new Post();
        $widget = CKEditor::widget([
            'view' => $view,
            'model' => $model,
            'attribute' => 'message',
            'clientOptions' => [
                'filebrowserUploadUrl' => '/',
            ],
        ]);

        $out = $view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $expected = 'alexantr.ckEditorWidget.registerOnChangeHandler(\'post-message\');';
        $this->assertContains($expected, $out);

        $expected = 'alexantr.ckEditorWidget.registerCsrfImageUploadHandler();';
        $this->assertContains($expected, $out);
    }

    public function testPresetName()
    {
        $view = $this->mockView();

        $this->mockWebApplication([
            'params' => [
                'ckeditor.testConfig' => [
                    'stylesSet' => false,
                ],
            ]
        ]);

        $model = new Post();
        $widget = CKEditor::widget([
            'view' => $view,
            'model' => $model,
            'attribute' => 'message',
            'presetName' => 'ckeditor.testConfig',
        ]);

        $out = $view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $expected = 'CKEDITOR.replace(\'post-message\', {"stylesSet":false});';
        $this->assertContains($expected, $out);
    }

    public function testAliases()
    {
        Yii::setAlias('@web', '/test');
        $view = $this->mockView();

        $model = new Post();
        $widget = CKEditor::widget([
            'view' => $view,
            'model' => $model,
            'attribute' => 'message',
            'clientOptions' => [
                'contentsCss' => '@web/css/style.css',
                'customConfig' => '@web/js/custom.js',
                'stylesSet' => 'testStyle:@web/js/styles.js',
            ],
        ]);

        $out = $view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $expected = 'CKEDITOR.replace(\'post-message\', {"contentsCss":"/test/css/style.css","customConfig":"/test/js/custom.js","stylesSet":"testStyle:/test/js/styles.js"});';
        $this->assertContains($expected, $out);
    }

    public function testContentsCssAliasesArray()
    {
        Yii::setAlias('@web', '/test');
        $view = $this->mockView();

        $model = new Post();
        $widget = CKEditor::widget([
            'view' => $view,
            'model' => $model,
            'attribute' => 'message',
            'clientOptions' => [
                'contentsCss' => ['@web/css/style1.css', '/test/css/style2.css'],
            ],
        ]);

        $out = $view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $expected = 'CKEDITOR.replace(\'post-message\', {"contentsCss":["/test/css/style1.css","/test/css/style2.css"]});';
        $this->assertContains($expected, $out);
    }

    public function testTemplatesFilesAliasesArray()
    {
        Yii::setAlias('@web', '/test');
        $view = $this->mockView();

        $model = new Post();
        $widget = CKEditor::widget([
            'view' => $view,
            'model' => $model,
            'attribute' => 'message',
            'clientOptions' => [
                'templates_files' => ['@web/js/templates1.js', '/test/js/templates2.js'],
            ],
        ]);

        $out = $view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $expected = 'CKEDITOR.replace(\'post-message\', {"templates_files":["/test/js/templates1.js","/test/js/templates2.js"]});';
        $this->assertContains($expected, $out);
    }
}
