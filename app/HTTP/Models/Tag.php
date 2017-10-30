<?php
  namespace HTTP\Models;
  class Tag extends BaseTable{
    protected $table='tags';

    public function getTags($type='all')
    {
      switch ($type) {
        case 'expense':
          $sql = $this->qb->table($this->table)->where('type','=',$type)->get().' or type is null';
          return $this->db->query($sql)->result();
          break;
        case 'income':
          $sql = $this->qb->table($this->table)->where('type','=',$type)->get().' or type is null';
          return $this->db->query($sql)->result();
          break;

        default:
          return $this->find('all');
          break;
      }
    }
  }
 ?>
