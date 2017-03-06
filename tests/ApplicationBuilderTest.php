<?php

namespace EBMApp\Tests;

use PHPUnit\Framework\TestCase;
use EBMApp\Tests\Factory\ApplicationFactory;
use EBMApp\Tests\Model\User;
use EBMApp\Tests\Migration\User as UserMigration;

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

        $message = 'Test if the application has all the fields';
        $this->assertArrayHasKey('username', $fields, $message);
        $this->assertArrayHasKey('email', $fields, $message);
        $this->assertArrayHasKey('place_of_birth_id', $fields, $message);

        $message = 'Test field has DB value';
        $username = $userApp->getField('username');
        $this->assertEquals('ebmuser', $username->getValue(), $message);
        $email = $userApp->getField('email');
        $this->assertEquals('ebmuser@mail.com', $email->getValue(), $message);

        // SectionOne tests
        $currentSection = $userApp->getCurrentSection();

        $message = 'Check if sectionOne is the current section because it is incomplete';
        $this->assertEquals('section-one', $currentSection->getSlug(), $message);

        $message = 'Check that only some fields belong to section 1';
        $this->assertArrayHasKey('username', $currentSection->getFields(), $message);
        $this->assertArrayHasKey('email', $currentSection->getFields(), $message);
        $this->assertArrayNotHasKey('place_of_birth_id', $currentSection->getFields(), $message);

        $message = 'Mark SectionOne as completed and check if it is complete';
        $currentSection->isComplete = true;
        $this->assertTrue($currentSection->isComplete(), $message);

        $message = 'Test that app is still incomplete';
        $this->assertFalse($userApp->isComplete(), $message);

        // Get the next section
        $currentSection = $userApp->getCurrentSection();

        $message = 'Check if sectionTwo is the current section because it is incomplete';
        $this->assertEquals('section-two', $currentSection->getSlug(), $message);

        $message = 'Check that only some fields belong to section 2';
        $this->assertArrayHasKey('place_of_birth_id', $currentSection->getFields(), $message);
        $this->assertArrayNotHasKey('username', $currentSection->getFields(), $message);
        $this->assertArrayNotHasKey('email', $currentSection->getFields(), $message);

        $message = 'Test that the current section is incomplete';
        $this->assertFalse($currentSection->isComplete(), $message);

        // Add a value to the current section field
        $user->place_of_birth_id = 1;
        $user->save();

        // Get the UserApplication
        $userApp = ApplicationFactory::create(1);

        $place_of_birth_id = $userApp->getField('place_of_birth_id');

        $message = 'Test that value was stored to the field';
        $this->assertEquals(1, $place_of_birth_id->getValue(), $message);

        // Get the updated current section if any of its fields matches alias param
        // Mark sectionOne as completed so sectionTwo is the currentSection
        $sectionOne = $userApp->getSectionByFieldAlias('username');
        $sectionOne->isComplete = true;
        $sectionTwo = $userApp->getSectionByFieldAlias('place_of_birth_id');

        $message = 'Test that the current section is complete';
        $this->assertTrue($sectionTwo->isComplete(), $message);

        $message = 'Test that all sections are complete';
        $this->assertTrue($userApp->isComplete(), $message);

        // Set username value as -1 and test the app again
        $username = $sectionOne->getField('username');
        $username->setValue(null);

        // Get the UserApplication
        $userApp = ApplicationFactory::create(1);
        
        $message = 'Test that app is incomplete';
        $this->assertFalse($userApp->isComplete(), $message);
    }
}










