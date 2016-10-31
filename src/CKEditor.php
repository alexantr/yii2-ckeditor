<?php

namespace alexantr\ckeditor;

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
}
