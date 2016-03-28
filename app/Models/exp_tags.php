<?php
  class ExpTags extends Table{

    protected $table='exp_tags';
    protected $primary_key ='id';
    protected $hasOne =['Tags'];

    public function  findExpTagsById($id){
      $result = $this->find('all',[
        'where'=>['exp_id','=',$id]
      ]);
      $model = new Tags();
      for($i=0;$i<count($result);$i++){
          $result[$i]->{'tags'} = $this->db->query($this->qb->table('tags')->where('id',"=",$result[$i]->{'tag_id'})->get())->first();
      }

      return $result;
    }

    public function expTagsData($allExpenses)
    {
      $EXP = new Expenses();
      $DATA = [];
      $exptags= [];
      foreach ($allExpenses as $expense) {
        $exptags[] = $this->findExpTagsById($expense->exp_id);
      }

      foreach ($exptags as $exptag) {
        foreach ($exptag as $tag) {
          if(isset($DATA[$tag->tags->name])){
            $DATA[$tag->tags->name]+= (int) $EXP->read($tag->exp_id)->get()->cost;
          }
          else{
            $DATA[$tag->tags->name] = (int) $EXP->read($tag->exp_id)->get()->cost;
          }

        }
      }

      return $DATA;
    }


  }
 ?>
