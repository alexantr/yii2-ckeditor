<?php

namespace alexantr\ckeditor;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\InputWidget;

/**
 * CKEditor input widget uses CKEditor 4
 * @link https://ckeditor.com/ckeditor-4/
 */
class CKEditor extends InputWidget
{
    /**
     * @var string CKEditor CDN base URL
     */
    public static $cdnBaseUrl = 'https://cdn.ckeditor.com/4.10.0/standard-all/';

    /**
     * @var array CKEditor options
     * @see https://docs.ckeditor.com/ckeditor4/latest/api/CKEDITOR_config.html
     */
    public $clientOptions = [];
    /**
     * @var string Path to preset with CKEditor options. Will be merged with $clientOptions.
     */
    public $presetPath = '@app/config/ckeditor.php';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->clientOptions = ArrayHelper::merge($this->getPresetConfig(), $this->clientOptions);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $input = $this->hasModel()
            ? Html::activeTextarea($this->model, $this->attribute, $this->options)
            : Html::textarea($this->name, $this->value, $this->options);
        $this->registerPlugin();
        return $input;
    }

    /**
     * Registers script
     */
    protected function registerPlugin()
    {
        $id = $this->options['id'];

        $view = $this->getView();
        WidgetAsset::register($view);

        $cdnBaseUrl = Html::encode(self::$cdnBaseUrl);
        $encodedOptions = !empty($this->clientOptions) ? Json::htmlEncode($this->clientOptions) : '{}';

        $view->registerJs("var CKEDITOR_BASEPATH = '$cdnBaseUrl';", View::POS_HEAD);
        $view->registerJs("alexantr.ckEditorWidget.setBaseUrl('$cdnBaseUrl');", View::POS_END);
        $view->registerJs("alexantr.ckEditorWidget.register('$id', $encodedOptions);", View::POS_END);
        if (isset($this->clientOptions['filebrowserUploadUrl']) || isset($this->clientOptions['filebrowserImageUploadUrl'])) {
            $view->registerJs("alexantr.ckEditorWidget.registerCsrfUploadHandler();", View::POS_END);
        }
    }

    /**
     * Get options config from preset
     * @return array
     */
    protected function getPresetConfig()
    {
        if (!empty($this->presetPath)) {
            $configPath = Yii::getAlias($this->presetPath);
            if (is_file($configPath)) {
                $config = include $configPath;
                return is_array($config) ? $config : [];
            }
        }
        return [];
    }
}
