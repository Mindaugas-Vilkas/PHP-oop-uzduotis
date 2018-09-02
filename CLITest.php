<?php

require 'cli.php';

class CLITest extends PHPUnit_Framework_TestCase
{
    private $command;

    protected function setUp()
    {
        $this->command = new CLI;
    }

    protected function tearDown()
    {
        $this->command = null;
    }



    public function testValidateSingle()
    {
        $result = $this->command->validateSingle('John;Doe;mail@mail.com;+1;+2;mary sue;');
        $this->assertEquals(array('John','Doe','mail@mail.com','+1','+2','mary sue'), $result);
    }

    public function testValidateSingleNoComment()
    {
        $result = $this->command->validateSingle('John;Doe;mail@mail.com;+1;+2;');
        $this->assertEquals(array('John','Doe','mail@mail.com','+1','+2',''), $result);
    }

    public function testValidateSingleNoEmail()
    {
        $result = $this->command->validateSingle('John;Doe;;+1;+2;');
        $this->assertEquals(null, $result);
    }

    public function testValidateSingleTooManySeparators()
    {
        $result = $this->command->validateSingle('John;Doe;;+1;+2;;');
        $this->assertEquals(null, $result);
    }



    public function testValidateName()
    {
        $result = $this->command->validateName('John;Doe');
        $this->assertEquals(array('John','Doe'), $result);
    }

    public function testValidateNameTooManySeparators()
    {
        $result = $this->command->validateName('John;Doe;;');
        $this->assertEquals(null, $result);
    }




    public function testValidateEmail()
    {
        $result = $this->command->validateEmail('quitarias@gmail.com');
        $this->assertEquals('quitarias@gmail.com', $result);
    }

    public function testValidateEmailDot()
    {
        $result = $this->command->validateEmail('quit.arias@gmail.com');
        $this->assertEquals('quit.arias@gmail.com', $result);
    }

    public function testValidateEmailStrange()
    {
        $result = $this->command->validateEmail('(comment)localpart@example.com');
        $this->assertEquals(null, $result);
    }

    public function testValidateEmailEndWithDot()
    {
        $result = $this->command->validateEmail('localpart.ending.with.dot.@example.com');
        $this->assertEquals(null, $result);
    }

}
