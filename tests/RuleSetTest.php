<?php

require_once 'setup.php';

use Joylei\Validation\Validator;
use Joylei\Validation\RuleSet;
use PHPUnit\Framework\TestCase;

class RuleSetTest extends TestCase{
    public function testSetup(){
        echo PHP_EOL;
    }

    public function testSetValidator(){
        $validator = new Validator();
        $ruleSet = new RuleSet('test');
        $ruleSet->setValidator($validator);
        $v = $ruleSet->end();
        $this->assertEquals($validator, $v);
    }

    public function testIsRequired(){
        $ruleSet = new RuleSet('test');
        $ruleSet->isRequired('is required');

        $this->assertEquals('is required', $ruleSet->validate(''));
        $this->assertEquals('is required', $ruleSet->validate(null));
        $this->assertNull($ruleSet->validate('...'));
    }

    public function testIsEmail(){
        $ruleSet = new RuleSet('test');
        $ruleSet->isEmail('is email');

        $this->assertEquals('is email', $ruleSet->validate('xxx'));
        $this->assertNull($ruleSet->validate('test@test.com'));
    }

    public function testHasMinLength(){
        $ruleSet = new RuleSet('test');
        $ruleSet->hasMinLength(2, 'min length 2');

        $this->assertEquals('min length 2', $ruleSet->validate('x'));
        $this->assertNull($ruleSet->validate('xx'));
    }

    public function testHasMaxLength(){
        $ruleSet = new RuleSet('test');
        $ruleSet->hasMaxLength(2, 'max length 2');

        $this->assertEquals('max length 2', $ruleSet->validate('xxx'));
        $this->assertNull($ruleSet->validate('xx'));
    }

    public function testIsNumber(){
        $ruleSet = new RuleSet('test');
        $ruleSet->isNumber('is number');

        $this->assertEquals('is number', $ruleSet->validate('xxx'));
        $this->assertNull($ruleSet->validate('20'));
        $this->assertNull($ruleSet->validate('-20'));
        $this->assertNull($ruleSet->validate('20.15'));
    }

    public function testHasRange(){
        $ruleSet = new RuleSet('test');
        $ruleSet->hasRange(5,10, "range(5,10)");

        $this->assertEquals('range(5,10)', $ruleSet->validate(20));
        $this->assertEquals('range(5,10)', $ruleSet->validate(4));
        $this->assertNull($ruleSet->validate('5'));
        $this->assertNull($ruleSet->validate('10'));
        $this->assertNull($ruleSet->validate('7'));
    }

    public function testHasMaxValue(){
        $ruleSet = new RuleSet('test');
        $ruleSet->hasMaxValue(10, 'max value 10');

        $this->assertEquals('max value 10', $ruleSet->validate('11'));
        $this->assertNull($ruleSet->validate('10'));
        $this->assertNull($ruleSet->validate('3'));
    }

    public function testHasMinValue(){
        $ruleSet = new RuleSet('test');
        $ruleSet->hasMinValue(5, 'min value 5');

        $this->assertEquals('min value 5', $ruleSet->validate('4'));
        $this->assertNull($ruleSet->validate('5'));
        $this->assertNull($ruleSet->validate('9'));
    }

    public function testHasPattern(){
        $ruleSet = new RuleSet('test');
        $ruleSet->hasPattern('/^\d+$/', 'digits');

        $this->assertEquals('digits', $ruleSet->validate('44abc'));
        $this->assertNull($ruleSet->validate('5'));
        $this->assertNull($ruleSet->validate('998'));
    }

    public function testCustom(){
        $ruleSet = new RuleSet('test');
        $ruleSet->custom('digits', function($field, $value){
            if (empty($value)) {
                return null;
            }
            $str = trim($value);
            $pattern = '/^\d+$/';
            if (preg_match($pattern, $str)) {
                return null;
            }
            return 'custom rule';
        });

        $this->assertEquals('custom rule', $ruleSet->validate('44abc'));
        $this->assertNull($ruleSet->validate('5'));
        $this->assertNull($ruleSet->validate('998'));
    }

    public function testMultipleRules(){
        $ruleSet = new RuleSet('test');
        $ruleSet->isRequired('required');
        $ruleSet->isNumber('is number');

        $err = $ruleSet->validate('');
        $this->assertEquals('required', $err);

        $err = $ruleSet->validate('ddd');
        $this->assertEquals('is number', $err);

        $err = $ruleSet->validate('555');
        $this->assertNull($err);
    }
}