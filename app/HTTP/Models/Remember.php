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

  public function clearOldSessions()
  {
    $duplicates = $this->db->query("select count(id), user_id from ". $this->table ." group by user_id having count(id) > 1;")->result();
    foreach ($duplicates as $dupe) {
      $sessions = $this->find('all',[
        "where" => ["user_id","=",$dupe->user_id],
        "fields"=> ["id"],
        "order" => ["id" => "asc"]
      ]);

      for ($i=0; $i < sizeof($sessions)-1 ; $i++) {
        $this->db->delete($this->table,["id","=",$sessions[$i]->id]);
      }
    }

  }
 }
 ?>
