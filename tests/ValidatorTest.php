<?php
require_once 'setup.php';

use Joylei\Validation\Validator;
use Joylei\Validation\RuleSet;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase{
    public function testSetup(){
        echo PHP_EOL;
    }

    public function testNoErrorNoRules(){
        $validator = new Validator();
        $model = [];
        $result = $validator->validate($model);
        $this->assertNotNull($result);
        $this->assertFalse($result->hasError());
    }

    public function testAddRuleSet(){
        $validator = new Validator();
        $ruleSet = new RuleSet('test');
        $validator->ruleFor('test', $ruleSet);

        $r = $validator->ruleFor('test');

        $this->assertEquals($ruleSet, $r);
    }

    public function testAddClosureRuleSet(){
        $validator = new Validator();
        $ruleSet = $validator->ruleFor('test',function(){

        });

        $r = $validator->ruleFor('test');

        $this->assertEquals($ruleSet, $r);
    }

    public function testValidate(){
        $validator = new Validator(false);
        $validator->ruleFor('field1',function(){
            $this->isRequired('required');
            $this->isNumber('is number');
        })->end()->ruleFor('field2', function(){
            $this->hasRange(5, 10, 'range(5,10)');
        });

        $result = $validator->validate([
            'field1' => 'aaa',
            'field2' => '20'
        ]);

        $this->assertTrue($result->hasError());
        $this->assertEquals('is number', $result->getErrorFor('field1'));
        $this->assertEquals('range(5,10)', $result->getErrorFor('field2'));
    }
}