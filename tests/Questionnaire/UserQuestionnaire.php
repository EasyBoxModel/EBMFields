<?php

namespace EBMQ\Tests\Questionnaire;

use EBMQ\Strategy\UserStrategy;
use EBMQ\Tests\Field\Option;
use EBMQ\Questionnaire\Questionnaire;

// Models
use EBMQ\Tests\Model\User;

class UserQuestionnaire extends Questionnaire
{
    public function __construct(Int $userId)
    {
        $user = User::find($userId);

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
