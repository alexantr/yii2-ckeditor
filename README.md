# CKEditor widget for Yii 2

This extension renders a [CKEditor](http://ckeditor.com/) widget for [Yii framework 2.0](http://www.yiiframework.com).

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
