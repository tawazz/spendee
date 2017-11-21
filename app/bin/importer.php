<?php
  require_once __DIR__.'/shell.php';

  //inteligent tagging
  $Carbon = $container->Carbon;
  $start = $Carbon->now()->subDays(3)->toDateString();
  $end = $Carbon->now()->toDateString();
  $container->auth = $container->User->find(7);
  $pb = $container->pb;
  $url = "http://importer.tich.us/import?from={$start}&to={$end}";
  $container->log->debug('expenses import',["url" => $url]);
  $response = $container->http->get($url);

  if ($response->getStatusCode() == 200) {
    $body = $response->getBody();
    $expenses = json_decode($body);
    foreach ($expenses as $exp) {
      $big_name = explode(" ",$exp->item);
      if(sizeof($big_name) >= 3){
        array_splice($big_name, 3);
      }
      $name = join(' ', $big_name);
      $data = [
        "name" => ucwords(strtolower(trim($name))),
        "date" => trim($exp->date),
        "cost" => $exp->amount,
        "user_id" => $container->auth->id
      ];
      \HTTP\Helpers\Utils::addExpense($container,(object) $data);
    }
    $pb->allDevices()->pushNote("Spendee - import completed",sizeof($expenses) . " expenses imported");
    \HTTP\Helpers\Utils::relatedTags($container,$start,$end);
    $container->log->debug('expenses import',["success" => true]);

  }else {
    $container->log->error('expenses import',["response" => $response->getBody()]);
    $pb->allDevices()->pushNote("Spendee - import failed","");
  }


 ?>
