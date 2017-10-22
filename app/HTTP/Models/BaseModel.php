<?php
namespace HTTP\Models;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Model;
use \Settings;
use \Tazzy\Utils\Validate;
  /**
   *
   */
  class BaseModel extends Model
  {
    protected $guarded = array();
    protected $primary_key ='id';
    protected $validation_rules;
    protected $is_valid = false;
    public $errors = null;

    public static function getPossbileEnumValues($name){
      $instance = new static; // create an instance of the model to be able to get the table name
      $settings = new Settings();
      $type = DB::select( DB::raw('SHOW COLUMNS FROM '.$settings->get('db.prefix').$instance->getTable().' WHERE Field = "'.$name.'"') )[0]->Type;
      preg_match('/^enum\((.*)\)$/', $type, $matches);
      $enum = array();
      foreach(explode(',', $matches[1]) as $value){
         $v = trim( $value, "'" );
         $enum[] = $v;
      }
      return $enum;
    }

    public function validate($source,$rules=[]){
      $this->errors = null;
      $validate = new Validate();
      if(!empty($rules)){
        if($validate->check($source,$rules)->passed()){
          return true;
        }else{
          $this->errors = $validate->errors();
          return false;
        }
      }else{
        if(!empty($this->validate)){
          if($validate->check($source,$this->validate)->passed()){
            return true;
          }else{
            $this->errors = $validate->errors();
            return false;
          }
        }else{
          return true;
        }
      }
      return false;
    }

    public function errors(){
      return $this->errors;
    }
  }

 ?>
