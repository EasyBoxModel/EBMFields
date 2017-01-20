<?php

namespace EBM\Tests;

use PHPUnit\Framework\TestCase;
use EBM\Field\Tests\Factory\FormFactory;
use EBM\Field\Tests\Model\User;
use EBM\Field\Tests\Migration\User as UserMigration;

class FormBuilderTest extends TestCase
{
    static function reset(){
        UserMigration::reset();
    }

    static function setUpBeforeClass()
    {
        self::reset();
    }

    public function testFormBuilder()
    {
        $user = new User;
        $user->username = 'ebmuser';
        $user->email = 'ebmuser@mail.com';
        $user->place_of_birth_id = 1;
        $user->save();

        $formFactory = FormFactory::get(1);
        $fields = $formFactory->getFields();

        // Test username field
        $username = $formFactory->getField('username');
        print_r($username->getValue());
    }
}










