<?php
    class Hash{
        public static function make($string, $salt=''){
            return hash('sha256',$string.$salt);
        }
        public static function check($known,$user){
          return hash_equals($known,$user);
        }
        public static function salt($length){
            return mcrypt_create_iv($length);
        }
        public static function unique(){
            return self::make(uniqid());
        }
        public function password($password){
          return password_hash($password,PASSWORD_BCRYPT,['cost'=>10]);
        }
        public function passwordCheck($password,$hash){
          return password_verify($password,$hash);
        }
    }
?>
