<?php

namespace EBMQ\Tests\Strategy;

use EBMQ\Field;

class UserStrategy
{
    /**
    * @description GLobal method that saves an email field into user model and reset the verification date record
    * @description so it is asked again
    * @param Field
    */
    public static function resetEmailVerificationDate(Field $field)
    {
        $model = $field->getModel();
        $column = $field->getFieldAttr('id');
        $value = $field->getValue();

        $model->EMAIL_VERIFICATION_DATE = null;
        $model->$column = $value;

        $model->save();
        
        return true;
    }
}
