<?php
  class Expenses extends Table{

    protected $table='expenses';
    protected $primary_key ='exp_id';

    public function totalExp($startDate,$endDate){
        $sql = $this->qb->sum($this->table,'cost')->where('user_id','=',$this->active_record)->andWhere("date",">=",$startDate)->andWhere("date","<=",$endDate)->get();
        return $this->db->query($sql)->first();
    }

     // get total cost of an expense/income between 2 dates

    public function spentOnProduct($name,$startDate,$endDate){
            $query = $this->db->query("select Sum(cost) AS cost,name from $this->table where user_id = ? and name LIKE ? and date >= ? and date <= ? ",array($this->active_record,$name,$startDate,$endDate));
            $query->first()->name = $name;
            return $query->first();
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
            $query = $this->db->query("select MAX(total) as max, name from (SELECT lcase(name) as name,Sum(cost) as total from $type where user_id= ? AND date >= ? and date <= ? group by name order by total DESC ) cost",array($this->active_record,$startDate,$endDate));
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
        $query = $this->db->query("SELECT lcase(name) as name,Sum(cost) as cost,exp_id as id from $type where user_id= ? and date >= ? and date <= ?  group by name order by cost DESC ",array($this->active_record,$startDate,$endDate));
        return json_encode($query->result());
    }

    public function activity($startDate,$endDate)
    {
      return $this->find('all',[
        'where'=>['user_id','=',$this->active_record],
        'andWhere'=>[['date','>=',$startDate],['date','<',$endDate]],
        'order'=>['date'=>'DESC']
      ]);
    }


  }
 ?>
