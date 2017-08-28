<?php
  namespace HTTP\Models;
  use \HTTP\Models\{Expense,Tag};

  class ExpenseTag extends BaseTable{

    protected $table='expense_tags';
    protected $primary_key ='id';
    protected $hasOne =[
      ['class' => \HTTP\Models\Tag::class, 'id' => 'tag_id']
    ];

    public function  findExpTagsById($id){
      $result = $this->find('all',[
        'where'=>['exp_id','=',$id]
      ]);
      $model = new Tag();
      for($i=0;$i<count($result);$i++){
          $result[$i]->{'tags'} = $this->db->query($this->qb->table('tags')->where('id',"=",$result[$i]->{'tag_id'})->get())->first();
      }

      return $result;
    }

    public function tagData($user_id,$startDate,$endDate)
    {
      $EXP = new Expense();
      $Tags = new Tag();
      $exptags= [];

      $allTags = $Tags->find('all');

      foreach ($allTags as $tag) {
        $amount = (int) $this->db->query("SELECT Sum(exp.cost ) as total FROM expense_tags as tag,expenses as exp where tag_id = ? and exp.id = tag.exp_id and  exp.date >= ? and exp.date <= ? and exp.user_id = ?",[$tag->id,$startDate,$endDate,$user_id])->first()->total;
        if($amount > 0){
          $exptags[$tag->name] =  $amount;
        }
      }
      return $exptags;
    }

    public function expTagsTotalSpent($user_id,$startDate,$endDate,$tags)
    {
      $EXP = new Expense();
      $TAGS = new Tag();
      $exptags= [];


      $allExps = $this->db->query("Select DISTINCT exp.exp_id, exp.cost,tag.tag_id from expenses as exp left join exp_tags as tag on exp.exp_id = tag.exp_id where exp.user_id = ? AND exp.date >= ? AND exp.date < ? ",[$user_id,$startDate,$endDate])->result();

      $addedExp = [];
      $spent = 0;
      foreach ($allExps as $exp)
      {
        foreach ($tags as $tag)
        {
          foreach ($tag as $tagData) {
            if($exp->tag_id == $tagData->id)
            {
              if (!in_array($exp->exp_id,$addedExp))
               {
                 $spent = $spent+$exp->cost;
                 array_push($addedExp,$exp->exp_id);
               }
            }
          }
        }

      }
      return $spent;
    }


  }
 ?>
