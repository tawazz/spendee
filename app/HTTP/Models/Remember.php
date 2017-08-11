<?php

namespace HTTP\Models;

 class Remember extends \Table
 {
   protected $table = 'remember';
   protected $primary_key ='id';

   public function saveSession($user_id,$hash)
   {
       $data = [
         'hash' => $hash,
         'user_id'=> $user_id
       ];
       $saved = $this->save($data);
       if(!$saved){
           dump($saved);
           die();
       }
   }

   public function deleteSession($id)
   {
       $this->db->delete($this->table,['user_id','=',$id]);
   }
 }
 ?>
