<?php

namespace alexantr\ckeditor;

use Yii;
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
     * @var string the directory that contains custom CKEditor's config.js, contents.css and styles.js files.
     * Can be direct path to CKEditor config file. This directory or file will be published by AssetManager if
     * [[presetBaseUrl]] not set.
     */
    public $presetBasePath;

    /**
     * @var string the base URL for directory with custom CKEditor's config.js, contents.css and styles.js files.
     * If [[presetBaseUrl]] is set, [[presetBasePath]] must be already Web-accessible.
     */
    public $presetBaseUrl;

    /**
     * @var string CKEditor styles name from custom styles.js.
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
     * Example with directory containing the files is not Web-accessible:
     * ```
     * [
     *     'presetBasePath' => '@app/path/to/preset',
     *     'presetStylesName' => 'fooStyles',
     *     'clientOptions' => [
     *         // ...
     *     ],
     * ]
     * ```
     * Example with [[presetBaseUrl]]:
     * ```
     * [
     *     'presetBasePath' => '@webroot/path/to/preset',
     *     'presetBaseUrl' => '@web/path/to/preset',
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
        if ($this->presetBasePath === null) {
            return;
        }

        if ($this->presetBaseUrl !== null) {
            $path = Yii::getAlias($this->presetBasePath);
            $url = Yii::getAlias($this->presetBaseUrl);
        } else {
            $am = $view->getAssetManager();
            list ($path, $url) = $am->publish($this->presetBasePath, [
                'only' => ['config.js', 'contents.css', 'styles.js'],
            ]);
        }

        if (!$path || !$url) {
            return;
        }

        $options = [];

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

        $this->clientOptions = ArrayHelper::merge($options, $this->clientOptions);
    }
}
