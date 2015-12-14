<?php
  /**
   *
   */
   /**
    *
    */
   class Remember extends Table
   {
     protected $table = 'session';
     protected $primary_key ='id';
   }

  class User extends Table
  {
    protected $table = 'users';
    protected $primary_key ='user_id';
    protected $hasOne = ['Remember'];
    protected $validate = [
      'firstname'=> array(
          'min'=> 2,
          'max'=>30
      ),
      'lastname'=> array(
          'min'=> 2,
          'max'=>30
      ),
      'username'=> array(
          'required'=> TRUE,
          'min'=> 3,
          'max'=>60,
          'unique'=>'users'
      ),
      'email'=> array(
          'required'=> TRUE,
          'min'=> 4,
          'max'=>60
      ),
      'password'=> array(
          'required'=> TRUE,
          'min'=> 6
      ),
      'password_again'=> array(
          'required'=> TRUE,
          'min'=> 6,
          'matches'=>'password'
      )
    ];

    public function exist ($data){
      $this->errors = null;
      $conditions = [
        "where"=>["username","=",$data['username']]
      ];
      $user = $this->find('first',$conditions);
      if($user){
        return $user;
      }else{
        //$this->errors = $user->errors();
        return false;
      }
    }

    public function activate($id){
      $user = $this->find('first',['where'=>['active_hash','=',Hash::make($id)]]);
      if($user){
        $this->read($user->user_id);
        $this->set([
          'active'=>true,
          'active_hash'=>null
        ]);
        return true;
      }
      return false;
    }

    public function remember($id,$rem_hash){
      $this->db->insert('session',[
        'hash' => $rem_hash,
        'user_id'=> $id
      ]);
    }
    public function removeRemember($id)
    {
      $this->db->delete('session',['user_id','=',$id]);
    }
    public function max()
    {
      $this->find('max','user_id');
    }
  }

 ?>
