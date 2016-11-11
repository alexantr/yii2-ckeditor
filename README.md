# CKEditor widget for Yii 2

This extension renders a [CKEditor](http://ckeditor.com/) widget for [Yii framework 2.0](http://www.yiiframework.com).

[![Latest Stable Version](https://poser.pugx.org/alexantr/yii2-ckeditor/v/stable)](https://packagist.org/packages/alexantr/yii2-ckeditor)
[![Total Downloads](https://poser.pugx.org/alexantr/yii2-ckeditor/downloads)](https://packagist.org/packages/alexantr/yii2-ckeditor)
[![License](https://poser.pugx.org/alexantr/yii2-ckeditor/license)](https://packagist.org/packages/alexantr/yii2-ckeditor)
[![Build Status](https://travis-ci.org/alexantr/yii2-ckeditor.svg?branch=master)](https://travis-ci.org/alexantr/yii2-ckeditor)

## Installation

Install extension through [composer](http://getcomposer.org/):

```
composer require alexantr/yii2-ckeditor
```

## CKEditor version

This extension works with stable `standard-all` build. The `standard-all` build includes all official CKSource
plugins with only those from the `standard` installation preset compiled into the `ckeditor.js` file and
enabled in the configuration.

## Usage

The following code in a view file would render a CKEditor widget:

```php
<?= alexantr\ckeditor\CKEditor::widget(['name' => 'attributeName']) ?>
```

Configuring the [CKEditor options](http://docs.ckeditor.com/#!/api/CKEDITOR.config) should be done
using the `clientOptions` attribute:

```php
<?= alexantr\ckeditor\CKEditor::widget([
    'name' => 'attributeName',
    'clientOptions' => [
        'extraPlugins' => 'autogrow,colorbutton,colordialog,iframe,justify,showblocks',
        'removePlugins' => 'resize',
        'autoGrow_maxHeight' => 900,
        'stylesSet' => [
            ['name' => 'Subscript', 'element' => 'sub'],
            ['name' => 'Superscript', 'element' => 'sup'],
        ],
    ],
]) ?>
```

If you want to use the CKEditor widget in an ActiveForm, it can be done like this:

```php
<?= $form->field($model, 'attributeName')->widget(alexantr\ckeditor\CKEditor::className()) ?>
```

## Using global configuration

To avoid repeating identical configuration in every widget you can set global configuration in
`Yii::$app->params`. Options from widget's `clientOptions` will be merged with this configuration. Use `presetName`
attribute for this functionality:

```php
<?= alexantr\ckeditor\CKEditor::widget([
    'name' => 'attributeName',
    'presetName' => 'ckeditor.customConfig', // will use options from Yii::$app->params['ckeditor.customConfig']
]) ?>
```

## Yii aliases

You can use aliases in `contentsCss`, `customConfig`, `stylesSet` and `templates_files` options.
They will be automatically translated by `Yii::getAlias()`:

```php
<?= alexantr\ckeditor\CKEditor::widget([
    'name' => 'attributeName',
    'clientOptions' => [
        'customConfig' => '@web/js/myconfig.js',
        'contentsCss' => [
            '@web/css/mysitestyles.css',
            '@web/css/anotherfile.css'
        ],
        'stylesSet' => 'mystyles:@web/editorstyles/styles.js',
        'extraPlugins' => 'templates',
        'templates_files' => [
            '@web/editor_templates/site_default.js',
            '@web/editor_templates/site_custom.js',
        ],
    ],
]) ?>
```

> **Note:** Path alias must be web-accessible. Only `@web` alias is web-accessible from predefined aliases. 
