<?php

  /**
   *
   */
  class Table
  {
    protected $table;
    protected $primary_key;
    protected $validate = [];
    protected $errors =[];
    protected $hasMany =[];
    protected $hasOne =[];
    protected $db;
    protected $active_record;
    protected $qb;
    function __construct(){
      $this->db = DB::connect();
      $this->active_record = null;
      $this->qb = new QueryBuilder();
      $this->primary_key = $this->primaryKey();
    }
    function __destruct() {
        $this->active_record = null;
    }
    public function save($fields,$key=NULL){
      $this->errors = null;
      if(!isset($key)){
        if(!isset($this->validate)){
          if($this->db->insert($this->table,$fields)){
            $this->active_record = $this->db->lastIndex();
            return $this->db->lastIndex();
          }else{
            $this->errors = $this->db->error_info();
            return false;
          }
        }else{
          if($this->validate($fields)){
            if($this->db->insert($this->table,$fields)){
              $this->active_record = $this->db->lastIndex();
              return $this->db->lastIndex();
            }else{
              $this->errors = $this->db->error_info();
              return false;
            }
          }else{
            return false;
          }
        }
      }else{
        $this->db->update($this->table,[$this->primary_key,'=',$key],$fields);
        $this->active_record = $this->db->lastIndex();
        return $this->db->lastIndex();
      }
    }
    public function read($key){
        $this->active_record = $key;
        return $this;
    }
    public function set($field,$value = null){
      $this->errors= null;
      if (isset($this->table)&&isset($this->active_record)&&isset($this->primary_key)){

        if(is_array($field)){
          if(!$this->db->update($this->table,[$this->primary_key,'=',$this->active_record],$field)->error()){
            return TRUE;
          }else{
            $this->errors = $this->db->error_info();
            return FALSE;
          }
        }else {
          if(!$this->db->update($this->table,[$this->primary_key,'=',$this->active_record],[$field => $value])->error()){
            return TRUE;
          }else{
            $this->errors = $this->db->error_info();
            return FALSE;
          }
        }
      }
      return FALSE;
    }
    public function get($id,$field=[]){
      if(!isset($field)){
        return $this->find('first',[
          'where'=>[$this->primary_key,'=',$id],
          'fields'=>$field
        ]);
      }else{
        return $this->find('first',[
        'where'=>[
                $this->primary_key,'=',$id
            ]
        ]);
      }
    }
    public function find($type = 'all', $conditions=[]){
      $this->errors = null;
      switch ($type) {
        case 'all':

        if(!$this->db->find($this->table,$conditions)){
            $result = $this->db->result();
            if(isset($this->hasMany)){
                foreach($this->hasMany as $many){
                    $model = new $many['class']();

                    for($i=0;$i<count($result);$i++){
                        $query = $model->find('all',[
                          'where' => [$many['id'],"=",$result[$i]->{$this->primary_key}]
                        ]);
                        foreach ($query as $value) {
                          unset($value->{$many['id']});
                        }
                        $result[$i]->{$model->table} = $query;
                    }
               }
            }
            if(isset($this->hasOne)){
                foreach($this->hasOne as $hasOne){
                    $model = new $hasOne['class']();
                    for($i=0;$i<count($result);$i++){
                      $query = $model->find('first',[
                        'where' => [$model->primary_key,"=",$result[$i]->{$hasOne['id']}]
                      ]);
                      $result[$i]->{$model->table} = $query;
                      unset($result[$i]->{$hasOne['id']});
                    }
               }
            }
              return $result;

          }else{
            $this->errors = $this->db->error_info();
            return false;
          }
          break;
        case 'first':
          if(!$this->db->find($this->table,$conditions)){
            $result = $this->db->first();
            if(isset($this->hasMany)){
              foreach($this->hasMany as $many){
                $model = new $many['class']();
                $query = $model->find('all',[
                  'where' => [$many['id'],"=",$result->{$this->primary_key}]
                ]);
                foreach ($query as $value) {
                  unset($value->{$many['id']});
                }
                $result->{$model->table} = $query;
              }
            }
            if(isset($this->hasOne)){
                foreach($this->hasOne as $hasOne){
                  $model = new $hasOne['class']();
                  for($i=0;$i<count($result);$i++){
                    $query = $model->find('first',[
                      'where' => [$model->primary_key,"=",$result->{$hasOne['id']}]
                    ]);
                    $result->{$model->table} = $query;
                    unset($result->{$hasOne['id']});
                  }
                }
            }
            return $result;
          }else{
            $this->errors = $this->db->error_info();
            return false;
          }
          break;
        case 'count':
          if(!$this->db->find($this->table,$conditions)){
            return $this->db->count();
          }else{
            $this->errors = $this->db->error_info();
            return false;
          }
          break;
        default :
          if(!$this->db->find($this->table,$conditions)){
            return $this->db->result();
          }else{
            $this->errors = $this->db->error_info();
            return false;
          }
        break;
      }
    }
    public function delete(){
        if (isset($this->table)&&isset($this->active_record)&&isset($this->primary_key)){
            if(!$this->db->delete($this->table,[$this->primary_key,"=",$this->active_record])->error()){
              return $this->db->error_info();
            }else {
              return TRUE;
            }
        }{
          return FALSE;
          //make sure you run read function first
        }
    }
    public function Reset(){
      $this->db->delete($this->table,[1,'=',1]);
      $this->db->query("ALTER TABLE ".$this->table." AUTO_INCREMENT = 1");
    }
    private function getModelData($result,$model,$relationship='hasMany'){
      switch ($relationship) {
        case 'hasOne':
          return $this->db->query($this->qb->table($model->table)->where($model->primary_key,"=",$result->{$result->primary_key})->get())->first();
          break;
        case 'hasMany':
            return $this->db->query($this->qb->table($model->table)->where($this->primary_key,"=",$result->{$this->primary_key})->get())->result();
          break;

        default:
          return false;
          break;
      }
      return False;
    }
    /*
    private function execHasMany($result){

        foreach($this->hasMany as $model){
            $model = new $model();
            for($i=0;$i<count($result);$i++){
                $modelData = $this->getModelData($result[$i],$model);
                $model->{$this->primary_key} = $result[$i]->{$this->primary_key};
                if(isset($model->hasMany)) {
                    foreach($model->hasMany as $relModel){
                        $relModel = new $relModel();
                        $model->{$relModel->table} = $this->getModelData($model,$relModel,'hasMany');
                        if (isset($relModel->hasOne)) {
                            foreach($relModel->hasOne as $Model){
                                $Model = new $Model();
                                $relModel->{$model->primary_key} = $model->{$this->primary_key};
                                $relModel->{$Model->table} = $this->getModelData($relModel,$Model,'hasOne');
                            }
                        }
                }
                $model->$modelData;
                var_dump($model);
                $result[$i]->{$model->table} = $model;
              }
            }
        }

        return $result;
    }
*/
    private function execHasMany($result){
      foreach($this->hasMany as $model){
          $model = new $model();
          for($i=0;$i<count($result);$i++){
              $modelData = $this->getModelData($result[$i],$model);
              if (isset($model->hasMany)) {
                foreach($this->hasMany as $relModel){
                    $relModel = new $relModel();
                    $model->{$this->primary_key} = $result[$i]->{$this->primary_key};
                    $model->{$relModel->table} = $this->getModelData($model,$relModel,'hasMany');
                    //var_dump($model);
                }
              }
              if (isset($model->hasOne)) {
                foreach($this->hasOne as $relModel){
                    $relModel = new $relModel();
                    $model->{$this->primary_key} = $result[$i]->{$this->primary_key};
                    $model->{$relModel->table} = $this->getModelData($model,$relModel,'hasOne');
                }
              }
              $result[$i]->{$model->table} = $model;
              //var_dump($result[$i]);
          }

          return $result;
      }
    }
    private function execHasOne($result){
      foreach($this->hasOne as $model){
          $model = new $model();
          for($i=0;$i<count($result);$i++){
              $result->{$model->table} = $this->db->query($this->qb->table($model->table)->where($model->primary_key,"=",$result->{$this->primary_key})->get())->first();
          }
      }
     return $result;
    }
    public function validate($source,$rules=[]){
      $this->errors = null;
      $validate = new Validate();
      if(!empty($rules)){
        if($validate->check($source,$rules)->passed()){
          return true;
        }else{
          $this->errors = $validate->errors();
          return false;
        }
      }else{
        if(!empty($this->validate)){
          if($validate->check($source,$this->validate)->passed()){
            return true;
          }else{
            $this->errors = $validate->errors();
            return false;
          }
        }else{
          return true;
        }
      }
      return false;
    }

    public function errors(){
      return $this->errors;
    }

    public function getPossbileEnumValues($name){
      $type = $this->db->query('SHOW COLUMNS FROM '.$this->table.' WHERE Field = "'.$name.'"')->first()->Type;
      preg_match('/^enum\((.*)\)$/', $type, $matches);
      $enum = [];
      foreach(explode(',', $matches[1]) as $value){
         $v = trim( $value, "'" );
         $enum[] = $v;
      }
      return $enum;
    }
    protected function primaryKey(){
        $query = $this->db->query("SHOW KEYS FROM ".$this->table." WHERE Key_name = 'PRIMARY'")->result();
        if(!$this->db->error()){
            return $query[0]->Column_name;
        }
        return FALSE;
    }
    private function tableColumns($table){
        return $this->db->tableColumns($table);
    }
  }


 ?>
