<?php

namespace EBMQ\Tests;

use PHPUnit\Framework\TestCase;
use EBMQ\Tests\Factory\QuestionnaireFactory;
use EBMQ\Tests\Model\User;
use EBMQ\Tests\Migration\User as UserMigration;

class QuestionnaireBuilderTest extends TestCase
{
    static function reset(){
        UserMigration::reset();
    }

    static function setUpBeforeClass()
    {
        self::reset();
    }

    public function testQuestionnaireBuilder()
    {
        $user = new User;
        $user->username = 'ebmuser';
        $user->email = 'ebmuser@mail.com';
        $user->place_of_birth_id = 1;
        $user->save();

        $qFactory = QuestionnaireFactory::get(1);
        $fields = $qFactory->getFields();

        // Test username field
        $username = $qFactory->getField('username');
        print_r($username->getValue());
    }
}










