<?php
require_once 'setup.php';

use Joylei\Validation\ValidationResult;
use Joylei\Validation\Validator;
use PHPUnit\Framework\TestCase;

class ValidationResultTest extends TestCase{
    public function testSetup(){
        echo PHP_EOL;
    }

    public function testNoErrorAdded(){
        $result = new ValidationResult();

        $this->assertFalse($result->hasError());

        $err = $result->getError();
        $this->assertNull($err);
    }

    public function testOneErrorAdded(){
        $result = new ValidationResult();
        $result->addError('test', 'test message');

        $this->assertTrue($result->hasError());

        $err = $result->getError();
        $this->assertEquals('test message', $err);

        $this->assertEquals(1, count($result->getErrorsFor('test')));
    }

    public function testTwoErrorsAdded(){
        $result = new ValidationResult();
        $result->addError('test', 'test message');
        $result->addError('test', 'err2');

        $this->assertTrue($result->hasError());

        $err = $result->getError();
        $this->assertEquals('test message', $err);

        $this->assertEquals(2, count($result->getErrorsFor('test')));
    }

    public function testErrorAddedForDifferentFields(){
        $result = new ValidationResult();
        $result->addError('test1', 'test1 message');
        $result->addError('test2', 'test1 message');

        $this->assertTrue($result->hasError());

        $err = $result->getError();
        $this->assertNotNull($err);

        $this->assertEquals(1, count($result->getErrorsFor('test1')));
        $this->assertEquals(1, count($result->getErrorsFor('test2')));
    }
}