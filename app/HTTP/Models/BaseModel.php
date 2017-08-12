<?php
namespace HTTP\Models;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Model;
use \Settings;
  /**
   *
   */
  class BaseModel extends Model
  {
    protected $guarded = array();
    protected $primary_key ='id';

    public static function getPossbileEnumValues($name){
      $instance = new static; // create an instance of the model to be able to get the table name
      $settings = new Settings();
      $type = DB::select( DB::raw('SHOW COLUMNS FROM '.$settings->get('mysql.prefix').$instance->getTable().' WHERE Field = "'.$name.'"') )[0]->Type;
      preg_match('/^enum\((.*)\)$/', $type, $matches);
      $enum = array();
      foreach(explode(',', $matches[1]) as $value){
         $v = trim( $value, "'" );
         $enum[] = $v;
      }
      return $enum;
    }
  }

 ?>
