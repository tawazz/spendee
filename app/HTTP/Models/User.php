<?php
  namespace HTTP\Models;
  use HTTP\Models\Remember;
  class User extends BaseModel
  {
    protected $table = 'users';
    protected $guarded = [];
    protected $validation_rules = [
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
      $user = $this->where('username',$data['username'])->first();
      if($user !== null){
        return $user;
      }else{
        return false;
      }
    }

    public function activate($id){
      $user = $this->where('active_hash',Hash::make($id))->first();
      if($user !== null ){
        $user->update([
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

    public function activateAccount()
    {
      $this->update([
        'active' => true,
        'active_hash'=> null
      ]);
    }
  }

 ?>
