<?php
  require_once __DIR__.'/shell.php';
  echo "normalize parking\n";
  $query = "update expenses set name = 'parking' where LOWER(name) like '%parking%'";
  \Tazzy\Database\DB::connect()->query($query);

  echo "normalize coles\n";
  $query = "update expenses set name = 'coles' where LOWER(name) like '%coles%'";
  \Tazzy\Database\DB::connect()->query($query);

  echo "normalize mcdonalds\n";
  $query = "update expenses set name = 'mcdonalds' where LOWER(name) like '%mcdonalds%'";
  \Tazzy\Database\DB::connect()->query($query);

  echo "normalize nandos\n";
  $query = "update expenses set name = 'nandos' where LOWER(name) like '%nandos%'";
  \Tazzy\Database\DB::connect()->query($query);

  echo "normalize car finance\n";
  $query = "update expenses set name = 'car' where LOWER(name) like '%st george%'";
  \Tazzy\Database\DB::connect()->query($query);

  //inteligent tagging
  $Carbon = $container->Carbon;
  $start = $Carbon->now()->month(1)->day(1)->toDateString();
  $end = $Carbon->now()->toDateString();
  $container->auth = $container->User->find(7);
  \HTTP\Helpers\Utils::relatedTags($container,$start,$end);

 ?>
