<?php
    class Hash{
        public static function make($string, $salt=''){
            return hash('sha256',$string.$salt);
        }
        public static function check($known,$user){
            if(!function_exists('hash_equals')) {
              function hash_equals($known, $user) {
                if(strlen($known) != strlen($user)) {
                  return false;
                } else {
                  $res = $known ^ $user;
                  $ret = 0;
                  for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
                  return !$ret;
                }
              }
            }else{
                return hash_equals($known,$user);
            }
          
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
