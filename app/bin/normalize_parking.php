<?php
  require_once __DIR__.'/shell.php';

  $query = "update expenses set name = 'parking' where LOWER(name) like '%parking%'";
  dump(\Tazzy\Database\DB::connect()->query($query));
 ?>
