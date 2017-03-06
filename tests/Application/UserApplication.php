<?php

namespace EBMApp\Tests\Application;

use EBMApp\Base\AbstractApplication;
use EBMApp\Strategy\UserStrategy;
use EBMApp\Tests\Field\Option;
use EBMApp\Tests\Section\SectionOne;
use EBMApp\Tests\Section\SectionTwo;

// Models
use EBMApp\Tests\Model\User;

class UserApplication extends AbstractApplication
{
    public function __construct(Int $id)
    {
        $this->addFields($id);
        $this->addSections();
    }

    public function addFields(Int $id)
    {
        $user = User::find($id);

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
