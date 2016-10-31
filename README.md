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

## Using presets

Preset is a directory containing custom `config.js`, `contents.css` and `styles.js` (all three files or
any one or two of them). You can set path or alias to this directory in widget configuration:

```php
<?= alexantr\ckeditor\CKEditor::widget([
    'name' => 'attributeName',
    'presetBasePath' => '@app/path/to/preset',
    'presetStylesName' => 'fooStyles',
]) ?>
```

This directory will be published by AssetManager and URLs to files in it will be added as CKEditor options
[customConfig](http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-customConfig),
[contentsCss](http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-contentsCss) and
[stylesSet](http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-stylesSet).

If directory already Web-accessible, you can set additional option `presetBaseUrl` which disables publishing:

```php
<?= alexantr\ckeditor\CKEditor::widget([
    'name' => 'attributeName',
    'presetBasePath' => '@webroot/path/to/preset', // must be set to check files existence
    'presetBaseUrl' => '@web/path/to/preset',
]) ?>
```

Option `presetStylesName` has to be equal to the name used in
[CKEDITOR.stylesSet.add](http://docs.ckeditor.com/#!/api/CKEDITOR.stylesSet-method-add).
Default value in this extension is 'presetStyles'. You can use it in your own `styles.js`:

```js
CKEDITOR.stylesSet.add('presetStyles', [
    {'name': 'Subscript', 'element': 'sub'},
    {'name': 'Superscript', 'element': 'sup'},
    {'name': 'Marked Text', 'element': 'mark'}
    // ...
]);
```

**Note**: Options `customConfig`, `contentsCss` and `stylesSet` in `clientOptions` have higher priority:

```php
<?= alexantr\ckeditor\CKEditor::widget([
    'name' => 'attributeName',
    'presetBasePath' => '@app/path/to/preset', // <-- with three options below this attribute has no effect
    'clientOptions' => [
        'customConfig' => '/myconfig.js',
        'contentsCss' => '/css/mysitestyles.css',
        'stylesSet' => 'mystyles:/editorstyles/styles.js',
    ],
]) ?>
``
