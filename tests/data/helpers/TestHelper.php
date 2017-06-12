<?php

namespace tests\data\helpers;

use Yii;

class TestHelper
{
    public static function getConfig()
    {
        return [
            'contentsCss' => Yii::getAlias('@web/css/style.css'),
            'customConfig' => Yii::getAlias('@web/js/custom.js'),
            'stylesSet' => 'mystyles',
        ];
    }
}
