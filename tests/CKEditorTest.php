<?php

namespace tests;

use alexantr\ckeditor\CKEditor;
use tests\data\models\Post;
use Yii;
use yii\helpers\Json;

class CKEditorTest extends TestCase
{
    public function testRenderWithModel()
    {
        $view = $this->mockView();

        $out = CKEditor::widget([
            'view' => $view,
            'model' => new Post(),
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

    public function testRegisterHandlersAndClientOptions()
    {
        $view = $this->mockView();

        $widget = CKEditor::widget([
            'view' => $view,
            'model' => new Post(),
            'attribute' => 'message',
            'clientOptions' => [
                'filebrowserUploadUrl' => '/',
            ],
        ]);

        $out = $view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $expected = 'alexantr.ckEditorWidget.register(\'post-message\', {"filebrowserUploadUrl":"\/"});';
        $this->assertContains($expected, $out);

        $expected = 'alexantr.ckEditorWidget.registerCsrfUploadHandler();';
        $this->assertContains($expected, $out);
    }

    public function testDefaultPresetPathWithOverride()
    {
        Yii::setAlias('@app/config', __DIR__ . '/data/config');

        $view = $this->mockView();

        $widget = CKEditor::widget([
            'view' => $view,
            'model' => new Post(),
            'attribute' => 'message',
            'clientOptions' => [
                'stylesSet' => false,
            ],
        ]);

        $out = $view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $expected_options = [
            'contentsCss' => '/css/style.css',
            'stylesSet' => false,
        ];
        $expected = 'alexantr.ckEditorWidget.register(\'post-message\', ' . Json::htmlEncode($expected_options) . ');';
        $this->assertContains($expected, $out);
    }

    public function testCustomPresetPath()
    {
        if (isset(Yii::$aliases['@app/config'])) {
            unset(Yii::$aliases['@app/config']);
        }

        $view = $this->mockView();

        $widget = CKEditor::widget([
            'view' => $view,
            'model' => new Post(),
            'attribute' => 'message',
            'presetPath' => '@app/data/config/other.php',
        ]);

        $out = $view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $expected_options = [
            'contentsCss' => '/css/style.css',
            'customConfig' => '/js/custom.js',
            'stylesSet' => 'otherstyles',
        ];
        $expected = 'alexantr.ckEditorWidget.register(\'post-message\', ' . Json::htmlEncode($expected_options) . ');';
        $this->assertContains($expected, $out);
    }
}
