<?php
  namespace HTTP\Models;
  use Tazzy\Database\Table;
  class BaseTable extends Table {

    public function getTable()
    {
      return $this->table;
    }
  }
 ?>
