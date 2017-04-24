<?php
namespace Joylei\Validation;

class ValidationResult
{
    private $errorMessages;
    
    public function hasError(){
        return is_array($this->errorMessages) && count($this->errorMessages)>0;
    }

    /**
    * add an error message to the result
    */
    public function addError($field, $message){
        if(is_null($this->errorMessages)){
            $this->errorMessages = [];
        }
        $errors = null;
        if(isset($this->errorMessages[$field])){
            $errors = $this->errorMessages[$field];
        }
        
        if(!$errors){
            $errors = [];
        }
        $errors[] = $message;//push
        $this->errorMessages[$field] = $errors;
    }

    /**
    * get all fields which is in error state
    */
    public function getFields(){
        if($this->hasError()){
            return array_keys($this->errorMessages);
        }
        return [];
    }

    /**
    * all errors; empty array if no error
    * @return array
    */
    public function getErrors(){
        $allErrors = [];
        if($this->hasError()){
            foreach($this->errorMessages as $field => $errors){
                $allErrors = array_merge($allErrors, $errors);
            }
        }
        return $allErrors;
    }

    /**
    * errors for field; empty array if no error
    * @return array
    */
    public function getErrorsFor($field){
        $errors = null;
        if($this->hasError()){
            $errors = $this->errorMessages[$field];
            return $errors;
        }
        if(!$errors){
            $errors = [];
        }
        return $errors;
    }

    /**
    * first error; null if no error
    * @return String
    */
    public function getError(){
        if($this->hasError()){
            foreach($this->errorMessages as $errors){
                if(is_array($errors) && count($errors)>0){
                    return $errors[0];
                }
            }
        }
        return null;
    }

    /**
    * first error for field; null if no error
    * @return String
    */
    public function getErrorFor($field){
        $errors = $this->getErrorsFor($field);
        return  is_array($errors) && count($errors)>0 ? $errors[0] : null;
    }
}