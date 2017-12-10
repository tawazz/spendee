<?php
  require_once __DIR__.'/shell.php';

  $normalize = ['coles' => 'coles', 'mcdonalds' => 'mcdonalds','nandos' => 'nandos', 'car' => 'st george'];

  foreach ($normalize as $key => $value) {
    echo "normalize {$key}\n";
    $query = "update expenses set name = '?' where LOWER(name) like '%?%'";
    \Tazzy\Database\DB::connect()->query($query,[$value,$key]);
  }

  //inteligent tagging
  $Carbon = $container->Carbon;
  $start = $Carbon->now()->month(1)->day(1)->toDateString();
  $end = $Carbon->now()->toDateString();
  $container->auth = $container->User->find(7);
  \HTTP\Helpers\Utils::relatedTags($container,$start,$end);

 ?>
