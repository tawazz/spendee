<?php
  namespace HTTP\Models;
  use \HTTP\Models\RecurringExpense;

  const DAILY = 'daily';
  const WEEKLY = 'weekly';
  const FORTNIGHTLY = 'fortnightly';
  const MONTHLY = 'monthly';
  const YEARLY = 'yearly';
  class Expense extends BaseTable{

    protected $table='expenses';
    protected $primary_key ='id';
    protected $hasMany =[
      ['class' => \HTTP\Models\ExpenseTag::class, 'id' => 'exp_id']
    ];
    private $RecurringExpense = null;

    public function get($id,$field=[])
    {
      $result = parent::get($id,$field);

      if ($result) {
        $tags = [];
        if (sizeof($result->expense_tags) > 0) {
          foreach ($result->expense_tags as $exptag) {
            array_push($tags,$exptag->tags->id);
          }
        }
        $result->tags = $tags;
        $result->is_recurring = boolval($result->is_recurring);
        $this->RecurringExpense = new RecurringExpense();
        $rc = $this->RecurringExpense->where('exp_id',$result->id)->first();
        if (isset($rc)) {
          $result->repeat =$rc->repeat;
          $result->end_repeat = isset($rc->end_repeat) ? 'date' : 'never';
          $result->repeat_until = isset($rc->end_repeat) ? $rc->end_repeat : null;
          $result->reminder = $rc->reminder;
        } else {
          $result->repeat = '0';
          $result->end_repeat = 'never';
          $result->repeat_until =  null;
          $result->reminder = '0';
        }
        $loc = new Location();
        $place = $loc->where('exp_id',$result->id)->first();
        if (isset($place)) {
          $result->location = (object) [
            "lat"  => $place->lat,
            "long" => $place->long,
            "name" => $place->name
          ];
        } else {
          $result->location = (object) [
            "lat"  => "",
            "long" => "",
            "name" => ""
          ];
        }

      }
      return $result;
    }

    public function totalExp($startDate,$endDate){
      $sql = $this->qb
            ->sum($this->table,'cost')
            ->where('user_id','=',$this->active_record)
            ->andWhere("date",">=",$startDate)
            ->andWhere("date","<",$endDate)
            ->get();
      $result =  $this->db->query($sql)->first();
      return isset($result->sum) ? $result->sum : 0;
    }

     // get total cost of an expense/income between 2 dates

    public function spentOnProduct($name,$startDate,$endDate){
      $query = $this->qb
              ->sum($this->table,'cost')
              ->where('user_id','=',$this->active_record)
              ->andWhere('name','LIKE',$name)
              ->andWhere('date','>=',$startDate)
              ->andWhere('date','<',$endDate)
              ->get();
      $result = $this->db->query($query);
      $result->first()->name = $name;
      return $result->first();
    }
    public function getProduct($name,$startDate,$endDate){
      return $this->find('all',[
        'where'=>['user_id','=',$this->active_record],
        'andWhere'=>[['date','>=',$startDate],['date','<',$endDate],['name','like',$name]],
        'order'=>['date'=>'DESC']
      ]);
    }
    // get biggest expense/income between 2 dates

    public function biggest($startDate,$endDate,$type='expenses'){
        if($this->active_record){
            $query = $this->db->query("select MAX(total) as max, name from (SELECT lcase(name) as name,Sum(cost) as total from $type where user_id= ? AND date >= ? and date < ? group by name order by total DESC ) cost",array($this->active_record,$startDate,$endDate));
            return $query->first();
        }else{
            return 0;
        }
        return 0;
    }

    public function yearlyAmnt($year,$type='expenses'){
        if($this->active_record){
            $query = $this->db->query("select SUM(cost) AS cost from $type where user_id = ? and Year(date)= ?",array($this->active_record,$year));
            $cost = (isset($query->first()->cost))? $query->first()->cost : 0 ;
            return $cost;
        }else{
            return 0;
        }
        return 0;
    }

    //all monthly expenses/ incomes

    public function allActivity($startDate,$endDate,$type='expenses'){
      $sql = "SELECT lcase(name) as name,Sum(cost) as cost from $type where user_id= ? and date >= ? and date < ?  group by name order by cost DESC ";
      $result = $this->db->query($sql,[$this->active_record,$startDate,$endDate])->result();
      return $result;
    }

    public function activity($startDate,$endDate)
    {
      return $this->find('all',[
        'where'=>['user_id','=',$this->active_record],
        'andWhere'=>[['date','>=',$startDate],['date','<',$endDate]],
        'order'=>['date'=>'DESC'],
      ]);
    }

    public function activityWithRelationships($startDate,$endDate)
    {
      $exp_ids = $this->find('all',[
        'where'=>['user_id','=',$this->active_record],
        'andWhere'=>[['date','>=',$startDate],['date','<',$endDate]],
        'order'=>['date'=>'DESC'],
        'fields'=>['id']
      ]);
      $expenses = [];
      foreach ($exp_ids as $exp) {
        array_push($expenses,$this->get($exp->id));
      }
      return $expenses;
    }

    private function repeatOptions($value)
    {
      switch ($value) {
        case 1:
        return DAILY;
        break;
        case 7:
        return WEEKLY;
        break;
        case 14:
        return FORTNIGHTLY;
        break;
        case 30:
        return MONTHLY;
        break;
        case 365:
        return YEARLY;
        break;
      }
    }

  }
 ?>
