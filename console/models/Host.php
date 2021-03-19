<?php

namespace console\models;

use yii\base\Model;

class Host extends Model
{
    public $port;

    public function rules()
    {
        return [
            ['port', 'integer']
        ];
    }
}
