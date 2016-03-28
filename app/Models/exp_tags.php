<?php
  class ExpTags extends Table{

    protected $table='exp_tags';
    protected $primary_key ='id';
    protected $hasOne =['Tags'];

    public function  findExpTagsById($id){
      return $this->find('all',[
        'where'=>['exp_id','=',$id]
      ]);
    }


  }
 ?>
