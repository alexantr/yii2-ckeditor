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
     * @var string Path to preset with CKEditor options
     */
    public $presetPath = '@app/config/ckeditor.php';

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            $content = Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            $content = Html::textarea($this->name, $this->value, $this->options);
        }
        $this->registerPlugin();
        return $content;
    }

    /**
     * Registers CKEditor plugin
     */
    protected function registerPlugin()
    {
        $id = $this->options['id'];

        $view = $this->getView();
        CKEditorWidgetAsset::register($view);

        $clientOptions = ArrayHelper::merge($this->getPresetConfig(), $this->clientOptions);
        $encodedOptions = !empty($clientOptions) ? Json::encode($clientOptions) : '{}';

        $js = [];
        $js[] = "CKEDITOR.replace('$id', $encodedOptions);";
        $js[] = "alexantr.ckEditorWidget.registerOnChangeHandler('$id');";

        if (isset($clientOptions['filebrowserUploadUrl']) || isset($clientOptions['filebrowserImageUploadUrl'])) {
            $js[] = "alexantr.ckEditorWidget.registerCsrfUploadHandler();";
        }

        $view->registerJs(implode("\n", $js));
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
