<?php
  require_once __DIR__.'/../../vendor/autoload.php';
  date_default_timezone_set('Australia/Perth');
  use \HTTP\Services\ServiceProvider;
  use \Slim\App;
  use \Slim\Http\Environment;

  $app = new App();
  $app->environment = Environment::mock([
      'REQUEST_URI' => '/'
  ]);
  $container = $app->getContainer();
  $container->register(new ServiceProvider());

  $today = $container->Carbon->now()->hour(0)->minute(0)->second(0);

  $expenses = $container->RecurringExpense->where('ended',false)->get();
  dump($expenses);
  foreach ($expenses as $exp) {
    $expense = $exp->expense();
    $exp_data = null;
    switch ($exp->repeat) {
      case '30':
        $repeat = $container->Carbon->parse($expense->date)->addMonths(1 * $exp->interval)->toDateString();
        break;
      case '365':
        $repeat = $container->Carbon->parse($expense->date)->addYears(1 * $exp->interval)->toDateString();
        break;
      case '14':
        $repeat = $container->Carbon->parse($expense->date)->addWeeks(2 * $exp->interval)->toDateString();
        break;
      case '7':
        $repeat = $container->Carbon->parse($expense->date)->addWeeks(1 * $exp->interval)->toDateString();
        break;
      case '1':
        $repeat = $container->Carbon->parse($expense->date)->addDays(1 * $exp->interval)->toDateString();
        break;
    }
    if ($today->eq($container->Carbon->parse($repeat))) {
      if (isset($exp->end_repeat)) {
        $end = $container->Carbon->parse($exp->end_repeat);
        if ($today->lte($end)) {
          $exp_data = [
              'name'=> $expense->name,
              'cost'=> $expense->cost,
              'date'=> $repeat,
              'user_id'=> $expense->user_id,
              'parent_id'=>$expense->id
          ];
        } else {
          $exp->ended = true;
          $exp->save();
          break;
        }
      } else {
        $exp_data = [
          'name'=> $expense->name,
          'cost'=> $expense->cost,
          'date'=> $repeat,
          'user_id'=> $expense->user_id,
          'parent_id'=>$expense->id
        ];
      }
      if (isset($exp_data)) {
        $exp_id = $container->Exp->save($exp_data);
        $exp->interval = $exp->interval+1;
        $exp->save();

        foreach ($expense->expense_tags as $T) {
            $tags_data = [
              'exp_id' => $exp_id,
              'tag_id' => $T->tags->id
            ];
            $tags_id = $container->ExpTags->save($tags_data);
        }
        $loc=$container->Location->where('exp_id',$expense->id)->first();
        if ($loc->exists) {
          $container->Location->insert([
            'name' => $loc->name,
            'lat' => $loc->lat,
            'long' => $loc->long,
            'exp_id' => $exp_id
          ]);
        }
        \HTTP\Helpers\Utils::clearExpRouteCache($app,$body->date);
        echo $container->Carbon->now()->toDayDateTimeString()." expense ".$expense->name." saved...\n";
      }
    }
  }

 ?>
