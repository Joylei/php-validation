<?php
namespace Joylei\Validation;

class Validator
{
    protected $rules;

    /**
    * if true, will stop on first error when validation;
    * otherwise will collect all errors.
    */
    protected $shortCircle;

    public function __construct($shortCircle = true)
    {
        $this->rules = [];
        $this->shortCircle = $shortCircle;
    }

    public function setShortCircle($shortCircle)
    {
        $this->shortCircle = $shortCircle;
    }

    /**
    * set up rule for $field; get an existing one or create a new one
    * 
    * usage:
    * $validator = new Validator();
    * //get or create a RuleSet
    * $rule = $validator->ruleFor('field_a');
    * //use closure
    * $rule = $validator->ruleFor('field_b', function(){
    *   $this->isRequired('the field is required');//$this is the current RuleSet object  
    * });
    * //set an existing RuleSet
    * $rule = new RuleSet('field_c');
    * $rule = $validator->ruleFor('field_c', $rule);
    * //use chained methods
    * $validator->ruleFor('field_a')->isRequired('the field is required')->end()
    *   ->ruleFor('field_b')->isRequired('the field is required');
    * @param string $field the field will be validated against
    * @param RuleSet|Closure $obj an RuleSet object or a function that setups RuleSet,optional
    * @return RuleSet
    */
    public function ruleFor($field, $obj = null)
    {
        if ($obj instanceof RuleSet) {
            $this->setRuleFor($field, $obj);
            return $obj;
        }
        
        if ($obj instanceof \Closure) {
            $rule = $this->getOrCreateRuleFor($field);
            $func = $obj->bindTo($rule);
            $func();
            return $rule;
        }

        if (!is_null($obj)) {
            throw new \InvalidArgumentException('unsupported type of obj');
        }
        return $this->getOrCreateRuleFor($field);
    }

    private function setRuleFor($field, $rule)
    {
        $rule->setValidator($this);
        $this->rules[$field] = $rule;
    }

    private function getOrCreateRuleFor($field)
    {
        $rule = null;
        if(isset($this->rules[$field])){
            $rule = $this->rules[$field];
        }else{
            $rule = new RuleSet($field);
            $this->setRuleFor($field, $rule);
        }
        return $rule;
    }

    public function validate($data)
    {
        if (is_null($data)) {
            throw new \InvalidArgumentException('data cannot be null');
        }

        $result = new ValidationResult();
        foreach ($this->rules as $field => $rule) {
            $value = $data[$field];
            $error = $rule->validate($value);
            if ($error) {
                $result->addError($field, $error);
                if ($this->shortCircle) {
                    break;
                }
            }
        }
        return $result;
    }
}
