<?php
  namespace HTTP\Models;
  use Tazzy\Database\Table;

  class BaseTable extends Table {
    protected $primary_key ='id';

    public function getTable()
    {
      return $this->table;
    }
  }
 ?>
