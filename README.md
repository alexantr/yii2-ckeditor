# CKEditor widget for Yii 2

This extension renders a [CKEditor](http://ckeditor.com/) widget for [Yii framework 2.0](http://www.yiiframework.com).

[![Latest Stable Version](https://img.shields.io/packagist/v/alexantr/yii2-ckeditor.svg)](https://packagist.org/packages/alexantr/yii2-ckeditor)
[![Total Downloads](https://img.shields.io/packagist/dt/alexantr/yii2-ckeditor.svg)](https://packagist.org/packages/alexantr/yii2-ckeditor)
[![License](https://img.shields.io/github/license/alexantr/yii2-ckeditor.svg)](https://raw.githubusercontent.com/alexantr/yii2-ckeditor/master/LICENSE)
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

> **Note:** Since version 2.0 the extension loads CKEditor from [CDN](https://cdn.ckeditor.com/).

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

## Using global configuration (presets)

To avoid repeating identical configuration in every widget you can set global configuration in
`@app/config/ckeditor.php`. Options from widget's `clientOptions` will be merged with this configuration.

You can change default path with `presetPath` attribute:

```php
<?= alexantr\ckeditor\CKEditor::widget([
    'name' => 'attributeName',
    'presetPath' => '@backend/config/my-ckeditor-config.php',
]) ?>
```
