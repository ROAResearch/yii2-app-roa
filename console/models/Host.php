<?php

namespace console\models;

use yii\base\Model;

class Host extends Model
{
    public $ipHost;

    public function rules()
    {
        return [
            ['ipHost', 'ip']
        ];
    }
}
