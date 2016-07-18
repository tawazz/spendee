<?php

    class Config{
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
