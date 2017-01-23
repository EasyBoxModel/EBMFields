<?php

namespace EBMQ\Tests;

use PHPUnit\Framework\TestCase;
use EBMQ\Tests\Factory\ApplicationFactory;
use EBMQ\Tests\Model\User;
use EBMQ\Tests\Migration\User as UserMigration;

class ApplicationBuilderTest extends TestCase
{
    static function reset(){
        UserMigration::reset();
    }

    static function setUpBeforeClass()
    {
        self::reset();
    }

    public function testApplicationBuilder()
    {
        $user = new User;
        $user->username = 'ebmuser';
        $user->email = 'ebmuser@mail.com';
        $user->save();

        // Get the UserApplication
        $userApp = ApplicationFactory::create(1);

        // Get its fields
        $fields = $userApp->getFields();

        // Test if the application has all the fields
        $this->assertArrayHasKey('username', $fields);
        $this->assertArrayHasKey('email', $fields);
        $this->assertArrayHasKey('place_of_birth_id', $fields);

        // Test field has DB value
        $username = $userApp->getField('username');
        $this->assertEquals('ebmuser', $username->getValue());
        $email = $userApp->getField('email');
        $this->assertEquals('ebmuser@mail.com', $email->getValue());

        // SectionOne tests
        $currentSection = $userApp->getCurrentSection();

        // Check if sectionOne is the current section because it's incomplete
        $this->assertEquals('section-one', $currentSection->getSlug());

        // Check that only some fields belong to this section
        $this->assertArrayHasKey('username', $currentSection->getFields());
        $this->assertArrayHasKey('email', $currentSection->getFields());
        $this->assertArrayNotHasKey('place_of_birth_id', $currentSection->getFields());

        // Mark SectionOne as completed and check if it's complete
        $currentSection->isComplete = true;
        $this->assertTrue($currentSection->isComplete());
        exit;

        // Get the next section
        $currentSection = $userApp->getCurrentSection();

        // Check if sectionTwo is the current section because it's incomplete
        $this->assertEquals('section-two', $currentSection->getSlug());

        // Check that only some fields belong to this section
        $this->assertArrayHasKey('place_of_birth_id', $currentSection->getFields());
        $this->assertArrayNotHasKey('username', $currentSection->getFields());
        $this->assertArrayNotHasKey('email', $currentSection->getFields());

        // Test that the current section is incomplete 
        $this->assertFalse($currentSection->isComplete());

        // Add a value to the current section field
        $user->place_of_birth_id = 1;
        $user->save();

        // Get the UserApplication
        $userApp = ApplicationFactory::create(1);

        // Test that all sections are complete
        $this->assertTrue($userApp->isComplete());
    }
}










