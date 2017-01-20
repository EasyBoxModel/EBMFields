<?php

namespace EBM\Field\Tests\Factory;

use EBM\Field\Form\BaseForm;
use EBM\Field\Tests\Form\UserForm;

class FormFactory
{
    public static function get(Int $id): BaseForm
    {
        return new UserForm($id);
    }
}
