<?php
  /**
   *
   */
  class QueryBuilder
  {
    private $query,$values;

    function __construct()
    {
      $this->query = "";
      $this->values =[];
    }
    public function fields($table,$fields){
        $sql ="";
        foreach ($fields as $val) {
            $sql.= $table.".". $val.",";
        }
        $sql = trim($sql,',');
        $this->query = "Select ". $sql. " FROM ". $table;
        return $this;
    }
    public function table($table,$action="select")
    {
      $action = strtolower($action);
      switch ($action) {
        case 'select':
          $this->query ="SELECT * FROM ".$table;
          break;
        case 'update':
          $this->query ="UPDATE ".$table." SET ";
          break;
        case 'delete':
          $this->query ="DELETE FROM ".$table;
          break;
        case 'insert':
          $this->query ="INSERT into ".$table;
          break;
        default:
        $this->query ="SELECT * FROM ".$table;
          break;
      }
      return $this;
    }
    public function join($table,$condition)
    {
      $this->query .= " INNER JOIN ".$table." ON ". $condition[0] ." ".$condition[1]." ".$condition[2];
      return $this;
    }
    public function where($col,$operator,$value)
    {
      $this->query .= " WHERE ".$col." ".$operator." '". $value."'";
      return $this;
    }

    public function andWhere($col,$operator,$value)
    {
      $this->query .= " AND ".$col." ".$operator." '". $value ."'";
      return $this;
    }

    public function orWhere($col,$operator,$value)
    {
      $this->query .= " OR ".$col." ".$operator." ". $value;
      return $this;
    }
    public function whereBtwn($col,$range)
    {
      $this->query .= " WHERE ".$col." BETWEEN '".$range[0]."' AND '". $range[1]."'";
      return $this;
    }
    public function whereIn($col,$range)
    {
      $this->query .= " WHERE ".$col." IN ( ";
      foreach ($range as $value) {
        $this->query .= "'".$value."' , ";
      }
      $this->query  = explode( " ", $this->query  );
      array_splice( $this->query ,-2);
      $this->query  = implode(" ",$this->query );
      $this->query .=")";
      return $this;
    }
    public function whereNotIn($col,$range)
    {
      $this->query .= " WHERE ".$col." BETWEEN '".$range[0]."' AND '". $range[1]."'";
      return $this;
    }
    public function max($table,$col)
    {
      $this->query = "SELECT MAX(".$col.") as max FROM ". $table;
      return $this;
    }
    public function min($table,$col)
    {
      $this->query = "SELECT MIN(".$col.") as min FROM ". $table;
      return $this;
    }
    public function count($table,$col)
    {
      $this->query = "SELECT COUNT(".$col.") as count FROM ". $table;
      return $this;
    }
    public function avarage($table,$col)
    {
      $this->query = "SELECT AVG(".$col.") as avg FROM ". $table;
      return $this;
    }
    public function sum ($table,$col)
    {
      $this->query = "SELECT SUM(".$col.") as sum FROM ". $table;
      return $this;
    }
    public function distinct($table,$col)
    {
      $this->query = "SELECT DISTINCT ".$col." FROM ". $table;
      return $this;
    }
    public function groupBy($col)
    {
      $this->query .= " GROUP BY ";
      foreach ($col as $value) {
          $this->query .=  $value ." , ";
      }
      $this->query  = explode( " ", $this->query  );
      array_splice( $this->query ,-2);
      $this->query  = implode(" ",$this->query );
      return $this;
    }
    public function orderBy($col)
    {
      $this->query .= " ORDER BY ";
      foreach ($col as $value) {
          $this->query .=  $value ." , ";
      }
      $this->query  = explode( " ", $this->query  );
      array_splice( $this->query ,-2);
      $this->query  = implode(" ",$this->query );
      return $this;
    }
    public function get()
    {
      $sql = $this->query;
      $this->query ="";
     // echo $sql;
      return $sql;
    }

  }

 ?>
