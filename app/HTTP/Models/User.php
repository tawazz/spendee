<?php
  namespace HTTP\Models;
  use HTTP\Models\Remember;
  class User extends \Tazzy\Database\Table
  {
    protected $table = 'users';
    protected $primary_key ='id';
    protected $hasMany = [
      ['class' => \HTTP\Models\Remember::class,'id' => 'user_id']
    ];
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
      $r = new Remember();
      $r->saveSession($id, $rem_hash);
    }
    public function removeRemember($id)
    {
      $r = new Remember();
      $r->deleteSession($id);
    }
    public function max()
    {
      $this->find('max','user_id');
    }
  }

 ?>
