<?php

namespace EBMFields\Form;

use EBMFields\Strategy\UserStrategy;
use EBMFields\Field\Option;

// Models
use App\User;

class ExampleForm extends AbstractBaseForm
{
    public function __construct(Int $userId)
    {
        $user = User::find($userId)

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
