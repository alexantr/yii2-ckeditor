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
     * Use custom config and styles from this extension
     * @var bool
     */
    public $useWidgetPreset = false;

    /**
     * Disable config from app params
     * @var bool
     */
    public $disableGlobalConfig = false;

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

        $options = $this->prepareClientOptions();

        $encodedOptions = !empty($options) ? Json::encode($options) : '{}';

        $js = [];
        $js[] = "CKEDITOR.replace('$id', $encodedOptions);";
        $js[] = "alexantr.ckEditorWidget.registerOnChangeHandler('$id');";

        if (isset($options['filebrowserUploadUrl']) || isset($options['filebrowserImageUploadUrl'])) {
            $js[] = "alexantr.ckEditorWidget.registerCsrfImageUploadHandler();";
        }

        $view->registerJs(implode("\n", $js));
    }

    /**
     * Prepare clientOptions
     * @return array
     */
    protected function prepareClientOptions()
    {
        $view = $this->getView();

        $options = [];

        // set custom widget config
        if ($this->useWidgetPreset) {
            $bundle = CKEditorPresetAsset::register($view);
            if (is_file($bundle->basePath . '/config.js')) {
                $options['customConfig'] = $bundle->baseUrl . '/config.js';
            }
            if (is_file($bundle->basePath . '/contents.css')) {
                $options['contentsCss'] = $bundle->baseUrl . '/contents.css';
            }
            if (is_file($bundle->basePath . '/styles.js')) {
                $options['stylesSet'] = 'presetStyles:' . $bundle->baseUrl . '/styles.js';
            }
        }

        // merge with config from app params
        if (!$this->disableGlobalConfig && isset(Yii::$app->params['ckeditor.config']) && is_array(Yii::$app->params['ckeditor.config'])) {
            $options = ArrayHelper::merge($options, Yii::$app->params['ckeditor.config']);
        }

        $options = ArrayHelper::merge($options, $this->clientOptions);

        return $options;
    }
}
