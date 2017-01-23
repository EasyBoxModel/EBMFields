<?php

namespace EBMQ\Tests\Application;

use EBMQ\Base\Application;
use EBMQ\Base\ApplicationInterface;
use EBMQ\Strategy\UserStrategy;
use EBMQ\Tests\Field\Option;
use EBMQ\Tests\Section\SectionOne;
use EBMQ\Tests\Section\SectionTwo;

// Models
use EBMQ\Tests\Model\User;

class UserApplication extends Application implements ApplicationInterface
{
    public function __construct(Int $userId)
    {
        $this->addFields($userId);
        $this->addSections();
    }

    public function addFields(Int $userId)
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

    public function addSections()
    {
        $username = $this->getField('username');
        $username->setLabel('Username');

        $email = $this->getField('email');
        $email->setLabel('Email');

        $this->addSection(new SectionOne([
            $username,
            $email,
            ], $this));

        $place_of_birth_id = $this->getField('place_of_birth_id');
        $place_of_birth_id->setLabel('Please select your place of birth');

        $this->addSection(new SectionTwo([
            $place_of_birth_id,
            ], $this));
    }
}
