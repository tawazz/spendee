<?php
namespace HTTP\Helpers;
use League\Csv\Reader;
use League\Csv\Writer;
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
    $app->auth = $app->User->get($data->user_id);
    $recurring = $app->RecurringExpense;
    $recurring = $recurring->where('exp_id',$data->id)->first();
    if (isset($recurring) && !$recurring->exists) {
      $recurring = null;
    } else {
      $expense = $app->Exp->get($data->id);
      $exp_date = $app->Carbon->parse($expense->date);
      $new_date = $app->Carbon->parse($data->date);
      $today = $app->Carbon->now()->hour(0)->minute(0)->second(0);
      if (isset($recurring)) {
        if ($exp_date->ne($new_date)) {
          $recurring->interval = 1;
          do {
            $expense->date = $new_date->toDateString();
            $recurring_date = self::getRecurringDate($app,$expense,$recurring);
            $recurring->interval += 1;
            $recurring->save();
            self::duplicateExp($app,$expense, $recurring_date->toDateString());
            $new_date->addDays(1);
            $repeat_until = isset($recurring->end_repeat) ? $app->Carbon->parse($recurring->end_repeat)->lte($new_date) : true;
          } while ($new_date->lte($exp_date) && $repeat_until );
        }
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
      'is_recurring'=>$isRecurring ? 1 : 0
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
      $updated = $app->Exp->get($data->id);
      self::clearExpRouteCache($app,$data->date);
      self::clearExpRouteCache($app,$updated->date);
      return $updated;
    } catch (\Exception $e) {
      $app->log->debug($e->getMessage(),$e->getTrace());
      return false;
    }
    return false;
  }

  public static function getRecurringDate($app,$expense,$recurring)
  {
    switch ($recurring->repeat) {
      case '30':
      return $app->Carbon->parse($expense->date)->addMonths(1 * $recurring->interval);
      break;
      case '365':
      return $app->Carbon->parse($expense->date)->addYears(1 * $recurring->interval);
      break;
      case '14':
      return $app->Carbon->parse($expense->date)->addWeeks(2 * $recurring->interval);
      break;
      case '7':
      return $app->Carbon->parse($expense->date)->addWeeks(1 * $recurring->interval);
      break;
      case '1':
      return $app->Carbon->parse($expense->date)->addDays(1 * $recurring->interval);
      break;
    }
  }

  public static function addExpense($app,$data) {

    $repeatOptions = $app->RecurringExpense->getPossbileEnumValues('repeat');
    $repeat = in_array($data->repeat, $repeatOptions) ? $data->repeat : null ;
    $recurring = null;
    $isRecurring = false;
    if (isset($repeat) && $repeat !=  '0') {
      $end_repeat = ($data->end_repeat == 'never') ? null : $data->repeat_until;
      $recurring = $app->RecurringExpense;
      $recurring->reminder = isset($data->reminder) ? $data->reminder : '0';
      $recurring->end_repeat = $end_repeat;
      $recurring->repeat = $repeat;
      $isRecurring = true;
    } else {
      $end_repeat = null;
    }

    $exp_data = [
      'name'=> $data->name,
      'cost'=> self::fixMoneyInput($data->cost),
      'date'=> $data->date,
      'user_id'=> $app->auth->id,
      'is_recurring'=>$isRecurring ? 1 : 0
    ];
    $exp_id = $app->Exp->save($exp_data);

    if (isset($recurring)) {
      $recurring->exp_id = $exp_id;
      $recurring->save();
    }

    foreach ($data->tags as $tag_id) {
      $tags_data = [
        'exp_id' => $exp_id,
        'tag_id' => $tag_id
      ];
      $app->ExpTags->save($tags_data);
    }

    if (isset($data->location) && !empty($data->location->lat) && !empty($data->location->long) && !empty($data->location->name) ) {
      $loc = $app->Location;
      $loc->name = $data->location->name;
      $loc->lat = $data->location->lat;
      $loc->long = $data->location->long;
      $loc->exp_id = $exp_id;
      $loc->save();
    }

    // clear cache
    $saved = $app->Exp->get($exp_id);
    self::clearExpRouteCache($app,$data->date);
    self::clearExpRouteCache($app,$saved->date);
    return $saved;
  }

  public static function duplicateExp($app,$expense,$date) {
    $exp_date = $app->Carbon->parse($date);
    $today = $app->Carbon->now()->hour(0)->minute(0)->second(0);
    if ($today->gte($exp_date)) {
      $exp_data = [
        'name'=> $expense->name,
        'cost'=> $expense->cost,
        'date'=> $date,
        'user_id'=> $expense->user_id,
        'parent_id'=>$expense->id
      ];

      $exp_id = $app->Exp->save($exp_data);
      foreach ($expense->expense_tags as $T) {
        $tags_data = [
          'exp_id' => $exp_id,
          'tag_id' => $T->tags->id
        ];
        $tags_id = $app->ExpTags->save($tags_data);
      }
      $loc=$app->Location->where('exp_id',$expense->id)->first();
      if (isset($loc) && $loc->exists) {
        $app->Location->insert([
          'name' => $loc->name,
          'lat' => $loc->lat,
          'long' => $loc->long,
          'exp_id' => $exp_id
        ]);
      }
    }
  }
  public static function addFromCsv($app)
  {
    $reader = Reader::createFromPath(__DIR__.'/../../bin/csv/file.csv');
    $reader->setHeaderOffset(0);
    $records = $reader->getRecords($reader->getHeader());
    foreach ($records as $offset => $record) {
      $record = (object) $record;
      $description = explode("-",$record->Description);
      if (sizeof($description > 3)) {
        $longData= explode(" ",trim($description[2]));
        $location = "";
        if(sizeof($longData > 3)){
          for ($i=2; $i < sizeof($longData) ; $i++) {
            if ($longData[$i] != "Date") {
              $location .= $longData[$i]." ";
            } else {
              break;
            }
          }
        }
      }
      $data = [
        "name" => trim($description[0]),
        "date" => $app->Carbon->createFromFormat('d/m/Y',trim($record->Date))->toDateString(),
        "cost" => intval($record->Debit) * - 1,
        "location" => trim($location)
      ];
      dump($data);
    }
  }
}


?>
