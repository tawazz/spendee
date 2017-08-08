<?php
require_once __DIR__ . '../../../config.php';
    class DB{
        private static $_instance = NULL;
        private $_pdo,
        $_query,
        $_error=false,
        $error_info,
        $_result,
        $qb,
        $count=0;
        private function __construct(){
            $this->qb = new QueryBuilder();
            try{
                $this->_pdo = new PDO(Config::get('db.driver').":host=". Config::get('db.host').";dbname=".Config::get('db.db'),Config::get('db.username'),Config::get('db.password'));
            }catch(PDOException $e){
                die($e->getMessage());
            }
        }
        public static function connect(){
            if(!isset(self::$_instance)){
                self::$_instance=new DB();
            }
            return self::$_instance;
        }
        public function lastIndex(){
            return $this->_pdo->lastInsertId();
        }
        public function query($sql,$params= array()){
            $this->_error = FALSE;
            if($this->_query = $this->_pdo->prepare($sql)){
                $i=1;
                if(count($params)){
                    foreach($params as $param){
                        $this->_query->bindValue($i,$param);
                        $i++;
                    }
                }
                if($this->_query->execute()){
                    $this->_result = $this->_query->fetchAll(PDO::FETCH_OBJ);
                    $this->_count = $this->_query->rowCount();
                }else{
                    $this->error_info  = [
                      'sql'=> $this->_query,
                      'error' => $this->_query->errorInfo()[2]
                    ];
                    var_dump($this->error_info);
                    $this->_error = TRUE;
                    return $this;
                }
            }
            return $this;
        }
        public function find($table,$conditions=array()){
          $sql = "SELECT ";
          $values = [];
          //  echo count($conditions);
          //no conditions
          if(count($conditions) < 1){
            $sql .= "* FROM ". $table;
          }else{
            //fields conditions
            if(isset($conditions['fields'])){
              foreach ($conditions['fields'] as $val) {
                $sql.= $val.",";
              }
              $sql = trim($sql,',');
              unset($conditions['fields']);
              $sql .= " FROM ". $table;
            } else{
              $sql .= " * FROM ". $table;
            }
            //between
            if(isset($conditions["between"])){
                $this->sql .= " WHERE ".$conditions["where"][0]." BETWEEN ".$conditions["where"][1][0]." AND ". $conditions["where"][1][1];
            }else{
              unset($conditions['between']);
            }
            //where clause
            if(isset($conditions["where"])){
              if(count($conditions["where"] === 3)){
                  $operators = array('=','>','<','>=','<=','!=','like');
                  $field = $conditions["where"][0];
                  $operator= $conditions["where"][1];
                  $values[] = $conditions["where"][2];
                  if(in_array($operator,$operators)){
                      $sql.=" WHERE {$table}." .$field." ". $operator." ? " ;
                  }
              }else{
                  unset($conditions['where']);
                }
            }
              //and where
              if(isset($conditions["andWhere"])){
                if(isset($conditions["where"])){
                  for($i=0;$i < count($conditions["andWhere"]);$i++){
                    if(count($conditions["andWhere"][$i] === 3)){
                        $operators = array('=','>','<','>=','<=','!=','like');
                        $field = $conditions["andWhere"][$i][0];
                        $operator= $conditions["andWhere"][$i][1];
                        $values[] = $conditions["andWhere"][$i][2];
                        if(in_array($operator,$operators)){
                            $sql.=" AND {$table}." .$field." ". $operator." ? " ;
                        }
                      }
                    }
                  }else{
                    unset($conditions['andWhere']);
                  }
              }
              //or where
              if(isset($conditions["orWhere"])){
                if(isset($conditions["where"])){
                  if(count($conditions["where"] === 3)){
                      $operators = array('=','>','<','>=','<=','!=','like');
                      $field = $conditions["where"][0];
                      $operator= $conditions["where"][1];
                      $values[] = $conditions["where"][2];
                      if(in_array($operator,$operators)){
                          $sql.=" OR {$table}." .$field." ". $operator." ? " ;
                          unset($conditions['orWhere']);
                      }
                    }else{
                      unset($conditions['orWhere']);
                    }
                  }
              }
              //order by
              if(isset($conditions["order"])){
                $sql.= " ORDER BY ";
                $o="";
                foreach ($conditions['order'] as $key => $val) {
                  $o.=$key." ".$val.",";
                }
                $o=preg_replace("/[^a-zA-Z ,_]/", "", $o);
                $o = trim($o,',');
                $sql .= $o;

                unset($conditions['order']);
              }
                //limit
              if(isset($conditions["limit"])){
                $sql.= " LIMIT ";
                if(count($conditions['limit']) == 2){
                    $sql .= $conditions['limit'][0] ." , ".$conditions['limit'][1];
                }

                unset($conditions['limit']);
              }
          }
          /*var_dump($sql);
            echo "<br>";
            var_dump($values);*/
            if(!$this->query($sql,$values)){
                return $this;
            }else{
                return false;
            }
            return FALSE;
        }

        public function action($action,$table,$where= array()){
            if(count($where === 3)){
                $operators = array('=','>','<','>=','<=','!=','like');
                $field = $where[0];
                $operator= $where[1];
                $value= $where[2];
                if(in_array($operator,$operators)){
                    $sql = $action. " FROM ". $table." WHERE " .$field." ". $operator." ? " ;
                    if(!$this->query($sql,array($value))->error()){
                        return $this;
                    }
                }
            }
            return FALSE;
        }
        /**
        get (table to get infomation,where parameters)
        example to get results
          $users = DB::getInstance()->query('Select * from users');
          if(!$users->count()){
              echo 'no such user found';
          }else{
            foreach($users->result() as $user){
                echo $user->username;
            }
          }
        **/
        public function get($table,$where){
            return $this->action('SELECT *',$table,$where);
        }
        public function findAll($table){
            return $this->query('SELECT * from '.$table);
        }
        public function delete($table,$where){
             return $this->action('DELETE ',$table,$where);
        }
        public function result(){
            return $this->_result;
        }
        public function first(){
            return (count( $this->_result)>0)? $this->_result[0]: null;
        }
        public function error(){
            return $this->_error;
        }
        public function error_info(){
            return $this->error_info;
        }
        public function count(){
            return $this->_count;
        }
        /**
        inserts(table name ,array containing parameters u wish to insert )
        example
        $insert = DB::getInstance()-> insert('users',array(
        'username'=> 'tawanda',
        'password'=> 'tawazz23',
        'id'=>1
       ));
        **/
        public function insert($table,$params= array()){
            if(count($params)){
                $keys= array_keys($params);
                $values= NULL;
                $V =[];
                $i=1;
                $sql = "insert into ".$table."(";
                foreach( $keys as $key){
                   $sql = $sql ."$key";
                   if($i<count($params)){
                       $sql.=', ';
                   }
                   $V[] = "$params[$key]";
                   $values.='?';
                   if($i<count($params)){
                       $values.=', ';
                       $i++;
                   }
                }
                $sql = $sql.")VALUES (".$values.")";
                //echo $sql;
                if(!$this->query($sql,$V)->error()){
                    return $this;
                } else {
		                return false;
                }
            }
            return FALSE;
        }
        /**
        update (table name, array with values {not assosiative array}, assosiative array with value parameter pairs)

         example
        $update = DB::getInstance()->update('users',array('id','=', '1'),array(
           'username'=> 'tawazz'
           ));
        **/
        public function update($table,$pk=array(),$params=array()){
            if(count($params)){
                $keys = array_keys($params);
                $values = NULL;
                $i=1;
                $sql = "UPDATE ".$table." SET ";
                foreach( $keys as $key){
                    $sql.= $key." = '$params[$key]'";
                    if($i<count($params)){
                       $sql .=', ';
                       $i++;
                   }
                }
                if(count($pk)===3){
                    $operators = array('=','>','<','>=','<=');
                    $field = $pk[0];
                    $operator= $pk[1];
                    $value= $pk[2];
                    if(in_array($operator,$operators)){
                        $sql .= " WHERE " .$field." ". $operator." ? " ;
                        if(!$this->query($sql,array($value))->error()){
                            return $this;
                        }
                    }
                }
            }
            return FALSE;
        }
        public function autoInc($table,$column){
            if(!$this->query("Alter table $table modify $column int(11) AUTO_INCREMENT")->error()){
                return $this;
            }
            return FALSE;
        }

        public function max($table,$col)
        {
            if(!$this->query($this->qb->max($table,$col)->get())){
              return $this;
            }
            return false;
        }

        public function primaryKey($table){
            $query = $this->query("SHOW KEYS FROM ".$table." WHERE Key_name = 'PRIMARY'")->result();
            if(!$this->error()){
              return $query[0]->Column_name;
            }
            return FALSE;
        }
        public function tableColumns($table){
          $query = $this->query("SELECT `COLUMN_NAME` AS 'column'
          FROM `INFORMATION_SCHEMA`.`COLUMNS`
          WHERE `TABLE_SCHEMA`= ".Config::get('db.db')."
              AND `TABLE_NAME`='{$table}';")->result();
          return $query;
        }
    }
?>
