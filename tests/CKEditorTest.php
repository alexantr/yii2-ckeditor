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

    public function testPresetAsArray()
    {
        $this->mockWebApplication([
            'params' => [
                'ckeditor.testConfig' => [
                    'contentsCss' => '/test/css/style.css',
                    'customConfig' => '/test/js/custom.js',
                    'stylesSet' => 'mystyles',
                ],
            ],
        ]);

        $this->assertContainsPresetConfig();
    }

    public function testPresetAsString()
    {
        $this->mockWebApplication([
            'params' => [
                'ckeditor.testConfig' => 'tests\data\helpers\TestHelper::getConfig',
            ],
        ]);

        $this->assertContainsPresetConfig();
    }

    public function testPresetAsClosure()
    {
        $this->mockWebApplication([
            'params' => [
                'ckeditor.testConfig' => function () {
                    return [
                        'contentsCss' => Yii::getAlias('@web/css/style.css'),
                        'customConfig' => Yii::getAlias('@web/js/custom.js'),
                        'stylesSet' => 'mystyles',
                    ];
                },
            ],
        ]);

        $this->assertContainsPresetConfig();
    }

    /**
     * Check preset
     */
    private function assertContainsPresetConfig()
    {
        Yii::setAlias('@web', '/test');
        $view = $this->mockView();

        $model = new Post();
        $widget = CKEditor::widget([
            'view' => $view,
            'model' => $model,
            'attribute' => 'message',
            'presetName' => 'ckeditor.testConfig',
            'clientOptions' => [
                'stylesSet' => false,
            ],
        ]);

        $out = $view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $expected_options = [
            '"contentsCss":"/test/css/style.css"',
            '"customConfig":"/test/js/custom.js"',
            '"stylesSet":false',
        ];
        $expected = 'CKEDITOR.replace(\'post-message\', {' . implode(',', $expected_options) . '});';
        $this->assertContains($expected, $out);
    }
}
