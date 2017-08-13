<?php
  require_once __DIR__.'/../../vendor/autoload.php';

  use \HTTP\Services\ServiceProvider;
  use \Slim\App;
  use \Slim\Http\Environment;

  $app = new App();
  $app->environment = Environment::mock([
      'REQUEST_URI' => '/'
  ]);
  $container = $app->getContainer();
  $container->register(new ServiceProvider());

  $expenses = $container->RecurringExpense->all();
  foreach ($expenses as $exp) {
    $expense = $exp->expense();
    switch ($expense->repeat) {
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
    $exp_data = [
        'name'=> $expense->name,
        'cost'=> $expense->cost,
        'date'=> $repeat,
        'user_id'=> $expense->user_id,
        'repeat'=> $expense->repeat
    ];
    isset($expense->end_repeat) ? $exp_data['end_repeat'] = $expense->end_repeat:"";
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

    $loc=$container->Location->where('exp_id',2697)->first();
    if ($loc->exists) {
      $container->Location->insert([
        'name' => $loc->name,
        'lat' => $loc->lat,
        'long' => $loc->long,
        'exp_id' => $exp_id
      ]);
    }
    echo $container->Carbon->now(new \DateTimeZone('Australia/Perth'))->toDayDateTimeString()." expense ".$expense->name." saved...\n";
  }

 ?>
