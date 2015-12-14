<?php
    $GLOBALS['config'] = array(
        'mysql' => array(
            'driver'=>'mysql',
            'host' => '127.0.0.1',
            'username'=> 'tawazzne_admin',
            'password' => '9lV2TDfJHfet',
            'db'=> 'tawazzne_spender'
        )
    );

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
