<?php
    class Validate{
      private $_passed = FALSE,
              $_errors = array(),
              $_db= NULL;

        public function __construct(){
            $this->_db = DB::connect();
        }
        public function check($source,$items=array()){
            foreach($items as $item=>$rules){
                foreach($rules as $rule=>$rule_value){
                    if(!array_key_exists($item,$source)){
                        break;
                    }
                    $value = $source[$item];
                    if($rule === 'required' && empty($value)){
                        $this->_errors[$item] = "{$item} is required. ";
                    }else if(!empty($value)){
                        switch($rule){
                            case 'min':
                                if(strlen($value) < $rule_value){
                                    $this->_errors[$item]="{$item} must be minimum of {$rule_value} characters.";
                                }
                                break;
                            case 'max':
                                if(strlen($value) > $rule_value){
                                    $this->_errors[$item]="{$item} must be maximum of {$rule_value} characters.";
                                }
                                break;
                            case 'matches':
                                if($value != $source[$rule_value]){
                                    $this->_errors[$item]="{$rule_value} must be match  {$item}.";
                                }
                                break;
                            case 'unique':
                                $check = $this->_db->get($rule_value,array($item,'=',$value));
                                if($check->count()){
                                    $this->_errors[$item]="{$item} already exist, choose another {$item}.";
                                }
                                break;
                        }
                    }
                }
            }
            if(empty($this->_errors)){
                $this->_passed = TRUE;
            }else{
              $this->_errors['post'] = $source;
            }
            return $this;
        }
        private function addError($error){
            $this->_errors[]= $error;
        }
        public function errors(){
            return $this->_errors;
        }
        public function passed(){
            return $this->_passed;
        }
    }
?>
