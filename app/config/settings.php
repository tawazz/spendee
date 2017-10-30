<?php
  /**
   * settings
   */
  require __DIR__.'/config.php';
  class Settings
  {
    public static function get($path=NULL){
        if ($path){
            $config = $GLOBALS['config'];
            $path = explode('.',$path);
            foreach($path as $bit){
                if(isset($config[$bit])){
                    $config = $config[$bit];
                }
            }
            return $config;
        }
    }
  }
 ?>
