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



    public function testValidateSingleProvider()
    {
        return array(
            array('John;Doe;mail@mail.com;+1;+2;mary sue;', array('John','Doe','mail@mail.com','+1','+2','mary sue')),
            array('John;Doe;mail@mail.com;+1;+2;', array('John','Doe','mail@mail.com','+1','+2','')),
            array('John;Doe;;+1;+2;', null),
            array('John;Doe;;+1;+2;;;', null),
        );
    }

    /**
    *   @dataProvider testValidateSingleProvider
    */
    public function testValidateSingle($data, $expected)
    {
        $result = $this->command->validateSingle($data);
        $this->assertEquals($expected, $result);
    }



    public function testValidateNameProvider()
    {
        return array(
            array('John;Doe', array('John','Doe')),
            array('John;Doe;;', null),
        );
    }

    /**
     *   @dataProvider testValidateNameProvider
     */
    public function testValidateName($data, $expected)
    {
        $result = $this->command->validateName($data);
        $this->assertEquals($expected, $result);
    }



    public function testValidateEmailProvider()
    {
        return array(
            array('quitarias@gmail.com', 'quitarias@gmail.com'),
            array('quit.arias@gmail.com', 'quit.arias@gmail.com'),
            array('(comment)localpart@example.com', null),
            array('localpart.ending.with.dot.@example.com', null),
        );
    }

    /**
     *   @dataProvider testValidateEmailProvider
     */
    public function testValidateEmail($data, $expected)
    {
        $result = $this->command->validateEmail($data);
        $this->assertEquals($expected, $result);
    }

}
