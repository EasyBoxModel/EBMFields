<?php

namespace EBMFields;

use EBMFields\Form\BaseForm;
use EBMFields\Form\ExampleForm;

class FormFactory
{
    public static function get(Int $id): BaseForm
    {
        return new ExampleForm($id);
    }
}
