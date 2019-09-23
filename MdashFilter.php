<?php

namespace mrssoft\mdash;

use Yii;
use yii\validators\Validator;

class MdashFilter extends Validator
{
    public $enableClientValidation = false;

    public $options = [];

    /**
     * @param \yii\base\Model $object
     * @param string $attribute
     * @throws \yii\base\InvalidConfigException
     */
    public function validateAttribute($object, $attribute)
    {
        if (count($this->options) === 0 && array_key_exists('mdash', Yii::$app->getComponents())) {
            $obj = Yii::$app->get('mdash');
        } else {
            $obj = new Mdash($this->options);
        }

        $object->{$attribute} = $obj->process($object->{$attribute});
    }
}