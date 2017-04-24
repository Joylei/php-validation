<?php
namespace Joylei\Validation;

class RuleSet
{
    protected $rules;
    protected $validator;
    public function __construct($field)
    {
        $this->field = $field;
        $this->rules = [];
    }

    public function setValidator($validator){
        $this->validator = $validator;
        return $this;
    }

    public function end(){
        return $this->validator;
    }

    public function isRequired($message)
    {
        if (empty($message)) {
            throw new \InvalidArgumentException('message cannot be null or empty');
        }
        $this->rules['require'] = function ($field, $value) use ($message) {
            if (empty($value)) {
                return $message;
            }
            return null;
        };
        return $this;
    }

    public function isEmail($message)
    {
        if (empty($message)) {
            throw new \InvalidArgumentException('message cannot be null or empty');
        }
        $this->rules['email'] = function ($field, $value) use ($message) {
            if (empty($value)) {
                return null;
            }
            $str = trim($value);
            if (preg_match('/[^@\s]+@[^@\s]+/i', $str)) {
                return null;
            }
            return $message;
        };
        return $this;
    }

    public function hasMinLength($length, string $message, $charset = 'UTF-8')
    {
        if (empty($message)) {
            throw new \InvalidArgumentException('message cannot be null or empty');
        }
        $this->rules['minLength'] = function ($field, $value) use ($length, $message, $charset) {
            if (empty($value)) {
                return null;
            }
            $str = trim($value);
            if (mb_strlen($str, $charset) >= $length) {
                return null;
            }
            return $message;
        };
        return $this;
    }

    public function hasMaxLength($length, $message, $charset = 'UTF-8')
    {
        if (empty($message)) {
            throw new \InvalidArgumentException('message cannot be null or empty');
        }
        $this->rules['maxLength'] = function ($field, $value) use ($length, $message, $charset) {
            if (empty($value)) {
                return null;
            }
            $str = trim($value);
            if (mb_strlen($str, $charset) <= $length) {
                return null;
            }
            return $message;
        };
        return $this;
    }

    public function isNumber(string $message)
    {
        if (empty($message)) {
            throw new \InvalidArgumentException('message cannot be null or empty');
        }
        $this->rules['number'] = function ($field, $value) use ($message) {
            if (empty($value)) {
                return null;
            }
            $str = trim($value);
            if (is_numeric($str)) {
                return null;
            }
            return $message;
        };
        return $this;
    }

    public function hasRange($min, $max, $message)
    {
        if (empty($message)) {
            throw new \InvalidArgumentException('message cannot be null or empty');
        }
        $this->rules['range'] = function ($field, $value) use ($min, $max, $message) {
            if (empty($value)) {
                return null;
            }
            $str = trim($value);
            if (is_numeric($str)) {
                $num = floatval($str);
                if ($num>=$min && $num <= $max) {
                    return null;
                }
                return $message;
            }
            return null;
        };
        return $this;
    }

    public function hasMaxValue($max, $message)
    {
        if (empty($message)) {
            throw new \InvalidArgumentException('message cannot be null or empty');
        }
        $this->rules['max'] = function ($field, $value) use ($max, $message) {
            if (empty($value)) {
                return null;
            }
            $str = trim($value);
            if (is_numeric($str)) {
                $num = floatval($str);
                if ($num <= $max) {
                    return null;
                }
                return $message;
            }
            return null;
        };
        return $this;
    }

    public function hasMinValue($min, $message)
    {
        if (empty($message)) {
            throw new \InvalidArgumentException('message cannot be null or empty');
        }
        $this->rules['min'] = function ($field, $value) use ($min, $message) {
            if (empty($value)) {
                return null;
            }
            $str = trim($value);
            if (is_numeric($str)) {
                $num = floatval($str);
                if ($num >= $min) {
                    return null;
                }
                return $message;
            }
            return null;
        };
        return $this;
    }

    public function hasPattern($pattern, $message)
    {
        if (is_null($pattern)) {
            throw new \InvalidArgumentException('pattern cannot be null');
        }
        if (empty($message)) {
            throw new \InvalidArgumentException('message cannot be null or empty');
        }

        $this->rules['pattern']  = function ($field, $value) use ($pattern, $message) {
            if (empty($value)) {
                return null;
            }
            $str = trim($value);
            if (preg_match($pattern, $str)) {
                return null;
            }
            return $message;
        };
        return $this;
    }

    public function custom($ruleName, $func)
    {
        if (!($func instanceof \Closure)) {
            throw new \InvalidArgumentException('func required to be Closure');
        }

        $this->rules[$ruleName] = $func;
        return $this;
    }

    public function validate($value)
    {
        $field = $this->field;
        foreach ($this->rules as $name => $rule) {
            $error = $rule($field, $value);
            if($error){
                return $error;
            }
        }
        return null;
    }
}