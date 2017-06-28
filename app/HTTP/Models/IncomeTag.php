<?php
  namespace HTTP\Models;
  class IncomeTag extends \Table{

    protected $table='inc_tags';
    protected $primary_key ='id';
    protected $hasOne =['Tags'];

    public function  findIncTagsById($id){
      $result = $this->find('all',[
        'where'=>['inc_id','=',$id]
      ]);
      $model = new Tags();
      for($i=0;$i<count($result);$i++){
          $result[$i]->{'tags'} = $this->db->query($this->qb->table('tags')->where('id',"=",$result[$i]->{'tag_id'})->get())->first();
      }

      return $result;
    }

    public function incTagsData($startDate,$endDate)
    {
      $INC = new Incomes();
      $Tags = new Tags();
      $inctags= [];

      $allTags = $Tags->find('all');

      foreach ($allTags as $tag) {
        $amount = (int) $this->db->query("SELECT Sum(inc.cost ) as total FROM inc_tags as tag,incomes as inc where tag_id = ? and inc.inc_id = tag.inc_id and  inc.date >= ? and inc.date <= ? ",[$tag->id,$startDate,$endDate])->first()->total;
        if($amount > 0){
          $inctags[$tag->name] =  $amount;
        }
      }
      return $inctags;
    }


  }
 ?>
