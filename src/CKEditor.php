<?php

namespace alexantr\ckeditor;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * Class CKEditor
 * @package alexantr\ckeditor
 */
class CKEditor extends InputWidget
{
    /**
     * @var array CKEditor options
     * @see http://docs.ckeditor.com/#!/api/CKEDITOR.config
     */
    public $clientOptions = [];

    /**
     * @var string Path to directory with custom CKEditor config.js, contents.css and styles.js files.
     * Can be path to config file only. This directory or file will be published with AssetManager.
     */
    public $presetPath;

    /**
     * @var string CKEditor styles name from custom styles.js
     * @see http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-stylesSet
     */
    public $presetStylesName = 'presetStyles';

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->value, $this->options);
        }
        $this->registerPlugin();
    }

    /**
     * Registers CKEditor plugin
     */
    protected function registerPlugin()
    {
        $id = $this->options['id'];

        $view = $this->getView();
        CKEditorWidgetAsset::register($view);

        $this->initPreset($view);

        $encodedOptions = !empty($this->clientOptions) ? Json::encode($this->clientOptions) : '{}';

        $js = [];
        $js[] = "CKEDITOR.replace('$id', $encodedOptions);";
        $js[] = "alexantr.ckEditorWidget.registerOnChangeHandler('$id');";

        if (isset($this->clientOptions['filebrowserUploadUrl']) || isset($this->clientOptions['filebrowserImageUploadUrl'])) {
            $js[] = "alexantr.ckEditorWidget.registerCsrfImageUploadHandler();";
        }

        $view->registerJs(implode("\n", $js));
    }

    /**
     * Set custom CKEditor files URLs to `clientOptions`.
     * Example:
     * ```
     * [
     *     'presetPath' => '@app/assets/foo-preset',
     *     'presetStylesName' => 'fooStyles',
     *     'clientOptions' => [
     *         // ...
     *     ],
     * ]
     * ```
     * @param \yii\web\View $view
     */
    protected function initPreset($view)
    {
        $options = [];

        if ($this->presetPath !== null) {
            $am = $view->getAssetManager();
            list ($path, $url) = $am->publish($this->presetPath, [
                'only' => ['config.js', 'contents.css', 'styles.js'],
            ]);
            if (is_dir($path)) {
                if (is_file($path . DIRECTORY_SEPARATOR . 'config.js')) {
                    $options['customConfig'] = $url . '/config.js';
                }
                if (is_file($path . DIRECTORY_SEPARATOR . 'contents.css')) {
                    $options['contentsCss'] = $url . '/contents.css';
                }
                if (is_file($path . DIRECTORY_SEPARATOR . 'styles.js') && !empty($this->presetStylesName)) {
                    $options['stylesSet'] = $this->presetStylesName . ':' . $url . '/styles.js';
                }
            } elseif (is_file($path)) {
                $options['customConfig'] = $url;
            }
        }

        $this->clientOptions = ArrayHelper::merge($options, $this->clientOptions);
    }
}
