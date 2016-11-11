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
        if ($this->presetName !== null && isset(Yii::$app->params[$this->presetName]) && is_array(Yii::$app->params[$this->presetName])) {
            $this->clientOptions = ArrayHelper::merge(Yii::$app->params[$this->presetName], $this->clientOptions);
        }
        $this->translateAliases();
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
     * Translates path aliases
     */
    protected function translateAliases()
    {
        if (isset($this->clientOptions['contentsCss'])) {
            $this->translateAliasesContentsCss();
        }
        if (isset($this->clientOptions['customConfig'])) {
            $this->translateAliasCustomConfig();
        }
        if (isset($this->clientOptions['stylesSet'])) {
            $this->translateAliasStylesSet();
        }
        if (isset($this->clientOptions['templates_files'])) {
            $this->translateAliasesTemplatesFiles();
        }
    }

    /**
     * Translates alias(es) in 'contentsCss'
     */
    protected function translateAliasesContentsCss()
    {
        if (is_array($this->clientOptions['contentsCss'])) {
            foreach ($this->clientOptions['contentsCss'] as $k => $alias) {
                if (strpos($alias, '@') === 0) {
                    $this->clientOptions['contentsCss'][$k] = Yii::getAlias($alias);
                }
            }
        } elseif (strpos($this->clientOptions['contentsCss'], '@') === 0) {
            $this->clientOptions['contentsCss'] = Yii::getAlias($this->clientOptions['contentsCss']);
        }
    }

    /**
     * Translates alias in 'customConfig'
     */
    protected function translateAliasCustomConfig()
    {
        if (strpos($this->clientOptions['customConfig'], '@') === 0) {
            $this->clientOptions['customConfig'] = Yii::getAlias($this->clientOptions['customConfig']);
        }
    }

    /**
     * Translates alias in 'stylesSet'
     */
    protected function translateAliasStylesSet()
    {
        if (is_string($this->clientOptions['stylesSet']) && strpos($this->clientOptions['stylesSet'], ':@') > 0) {
            $alias_parts = explode(':', $this->clientOptions['stylesSet'], 2);
            if (isset($alias_parts[1]) && strpos($alias_parts[1], '@') === 0) {
                $this->clientOptions['stylesSet'] = $alias_parts[0] . ':' . Yii::getAlias($alias_parts[1]);
            }
        }
    }

    /**
     * Translates aliases in 'templates_files'
     */
    protected function translateAliasesTemplatesFiles()
    {
        if (is_array($this->clientOptions['templates_files'])) {
            foreach ($this->clientOptions['templates_files'] as $k => $alias) {
                if (strpos($alias, '@') === 0) {
                    $this->clientOptions['templates_files'][$k] = Yii::getAlias($alias);
                }
            }
        }
    }
}
