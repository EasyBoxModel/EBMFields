<?php

namespace EBMApp\Tests\Application;

use EBMApp\Base\Application;
use EBMApp\Base\ApplicationInterface;
use EBMApp\Strategy\UserStrategy;
use EBMApp\Tests\Field\Option;
use EBMApp\Tests\Section\SectionOne;
use EBMApp\Tests\Section\SectionTwo;

// Models
use EBMApp\Tests\Model\User;

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
