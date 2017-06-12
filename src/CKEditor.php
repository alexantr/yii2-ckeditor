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
     * @var string param name in `Yii::$app->params` with CKEditor predefined config.
     */
    public $presetName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->presetName !== null) {
            $this->clientOptions = ArrayHelper::merge($this->getPresetConfig($this->presetName), $this->clientOptions);
        }
    }

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
     * Get options config from preset
     * @param string $presetName
     * @return array
     */
    protected function getPresetConfig($presetName)
    {
        $config = isset(Yii::$app->params[$presetName]) ? Yii::$app->params[$presetName] : [];
        if ((is_string($config) && is_callable($config)) || $config instanceof \Closure) {
            $config = call_user_func($config);
        }
        return is_array($config) ? $config : [];
    }
}
