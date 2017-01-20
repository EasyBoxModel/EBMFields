<?php

namespace EBM\Field\Tests\Form;

use EBM\Field\Strategy\UserStrategy;
use EBM\Field\Tests\Field\Option;
use EBM\Field\Form\BaseForm;

// Models
use EBM\Field\Tests\Model\User;

class UserForm extends BaseForm
{
    public function __construct(Int $userId)
    {
        $user = User::find($userId);

        // DatosBásicos
        $this->addField('username', $user)
            ->setValue();

        $this->addField('email', $user)
            ->setSaveStrategy(array(UserStrategy::class, 'resetEmailVerificationDate'))
            ->setValue();

        $this->addField('place_of_birth_id', $user)
            ->setValue()
            ->setOptions(Option::getLuPlaceOfBirth());
    }
}
