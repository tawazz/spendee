<?php
namespace HTTP\Helpers;
date_default_timezone_set('Australia/Perth');
/**
 *
 */
class Utils
{

  public static function clearExpRouteCache($app,$date){
    // clear cache
    $date = $app->Carbon->parse($date);
    $cache_keys = [
      'api.expenses.get.'.$app->auth->id.'.'.$date->year.'.'.$date->month,
      'api.totals.'.$app->auth->id.'.'.$date->year.'.'.$date->month,
      'api.exp.tags'.$app->auth->id.'.'.$date->year.'.'.$date->month
    ];
    $app->cache->deleteMultiple($cache_keys);
  }

  public static function fixMoneyInput($money){
    $money = str_replace( ',', '',$money);
    $money = str_replace('$','',$money);
    return $money;
  }

  public static function sendReminders($container)
  {
      $pb = $container->pb;
      $Carbon =$container->Carbon;
      $User = $container->User;

      $today = $Carbon->now()->hour(0)->minute(0)->second(0);
      $expenses = $container->RecurringExpense->where('ended',false)->get();
      $reminders = [];
      echo "[ ".$Carbon->now()->toDayDateTimeString(). " ] running job notifications \n";
      foreach ($expenses as $exp) {
        $expense = $exp->expense();
        $container->auth = $User->get($expense->user_id);
        $repeat = null;

        switch ($exp->repeat) {
          case '30':
            $repeat = $Carbon->parse($expense->date)->addMonths(1 * $exp->interval);
            array_push($reminders,self::getReminder($exp,$repeat,$today));
            break;
          case '365':
            $repeat = $Carbon->parse($expense->date)->addYears(1 * $exp->interval);
            array_push($reminders,self::getReminder($exp,$repeat,$today ));
            break;
          case '14':
            $repeat = $Carbon->parse($expense->date)->addWeeks(2 * $exp->interval);
            array_push($reminders,self::getReminder($exp,$repeat,$today ));
            break;
          case '7':
            $repeat = $Carbon->parse($expense->date)->addWeeks(1 * $exp->interval);
            array_push($reminders,self::getReminder($exp,$repeat,$today));
            break;
          case '1':
            $repeat = $Carbon->parse($expense->date)->addDays(1 * $exp->interval);
            array_push($reminders,self::getReminder($exp,$repeat,$today));
            break;
        }
      }

      foreach ($reminders as $reminder) {
        if (isset($reminder)) {
          $pb->allDevices()->pushNote("Spendee - Recurring Expenses",$reminder);
        }
      }
      $container->log->info('Notifications Sent...');
      echo $Carbon->now()->toDayDateTimeString()." Notifications Sent...\n";

  }

  public static function getReminder($exp,$repeat,$today)
  {
    if (isset($exp->reminder)) {
      if ($repeat->subDays(((int) $exp->reminder) * -1)->eq($today)) {
        $expense = $exp->expense();
        return "Expense {$expense->name} - $".$expense->cost." to be added on {$repeat->addDay()->format('l jS \\of F Y ')}";
      }
    }
  }

  public static function updateExpense($app,$data){

    $recurring = $app->RecurringExpense;
    $recurring = $recurring->where('exp_id',$data->id)->first();

    if (!$recurring->exists) {
      $recurring = null;
    } else {
      $expense = $app->Exp->get($data->id);
      $exp_date = $app->Carbon->parse($expense->date);
      $new_date = $app->Carbon->parse($data->date);
      $today = $app->Carbon->now()->hour(0)->minute(0)->second(0);
      if ($exp_date->ne($new_date)) {
        do {
          $recurring_date = self::getRecurringDate($expense,$recurring);
          if ($recurring_date->eq($exp_date)) {
            $recurring->interval += 1;
            // TODO: duplicate expense
          }
          $exp_date->addDays(1);
        } while ($exp_date->lt($today));
      }
    }
    $repeatOptions = $app->RecurringExpense->getPossbileEnumValues('repeat');
    $repeat = in_array($data->repeat, $repeatOptions) ? $data->repeat : null ;

    $isRecurring = false;

    if (isset($repeat) && $repeat !=  '0') {
      $end_repeat = ($data->end_repeat == 'never') ? null : $data->repeat_until;
      $recurring = isset($recurring) ? $recurring : $app->RecurringExpense;
      $recurring->reminder = isset($data->reminder) ? $data->reminder : '0';
      $recurring->end_repeat = $end_repeat;
      $recurring->repeat = $repeat;
      $isRecurring = true;
    } else {
      $end_repeat = null;
      $app->Exp->raw("delete from {$app->RecurringExpense->getTable()} where exp_id = {$data->id}");
    }

    $exp_data = [
        'name'=> $data->name,
        'cost'=> self::fixMoneyInput($data->cost),
        'date'=> $data->date,
        'user_id'=> $data->user_id,
        'is_recurring'=>$isRecurring
    ];

    try {
      $app->Exp->read($data->id)->set($exp_data);

      if (isset($recurring)) {
        $recurring->exp_id = $data->id;
        $recurring->save();
      }

      $app->ExpTags->raw("delete from {$app->ExpTags->getTable()} where exp_id = {$data->id}");
      foreach ($data->tags as $tag_id) {
        $tags_data = [
          'exp_id' => $data->id,
          'tag_id' => $tag_id
        ];
        $app->ExpTags->save($tags_data);
      }

      $app->Exp->raw("delete from {$app->Location->getTable()} where exp_id = {$data->id}");
      if (isset($data->location) && !empty($data->location->lat) && !empty($data->location->long) && !empty($data->location->name) ) {
        $loc = $app->Location;
        $loc->name = $data->location->name;
        $loc->lat = $data->location->lat;
        $loc->long = $data->location->long;
        $loc->exp_id = $data->id;
        $loc->save();
      }

      // clear cache
      self::clearExpRouteCache($app,$data->date);

      return $app->Exp->get($data->id);
    } catch (\Exception $e) {
      return false;
    }
    return false;
  }

  public function getRecurringDate($expense,$recurring)
  {
    switch ($recurring->repeat) {
      case '30':
        return $Carbon->parse($expense->date)->addMonths(1 * $recurring->interval)->toDateString();
        break;
      case '365':
        return $Carbon->parse($expense->date)->addYears(1 * $recurring->interval)->toDateString();
        break;
      case '14':
        return $Carbon->parse($expense->date)->addWeeks(2 * $recurring->interval)->toDateString();
        break;
      case '7':
        return $Carbon->parse($expense->date)->addWeeks(1 * $recurring->interval)->toDateString();
        break;
      case '1':
        return $Carbon->parse($expense->date)->addDays(1 * $recurring->interval)->toDateString();
        break;
    }
  }
}


 ?>
