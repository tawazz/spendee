<?php
namespace HTTP\Helpers;
use League\Csv\Reader;
use League\Csv\Writer;
use \Symfony\Component\Filesystem\Filesystem;
date_default_timezone_set('Australia/Perth');

const CLIENT_ID = "0JOOESNULVBUOHVDNPVU5OMQG1JF0FLSFTOLTLHE3NAED3YZ";
const CLIENT_SECRET= "JLQV5FM2KKDGYFJJA5FOT11YNY1T15MGJ2AXMW5YVLN15R5Y";
/**
*
*/
class Utils
{
  public static function cacheClear($container){
    $cache = $container->cache;
    $cache->clear();
    $filesystem = new Filesystem();
    $views_cache ='/app/app/views/cache';

    if ($filesystem->exists($views_cache)) {
        $filesystem->remove($views_cache);
        $container->log->debug('View Cache Cleared');
    }
    $container->log->debug('Cache Cleared');
  }

  public static function clearExpRouteCache($app,$date){
    // clear cache
    $date = $app->Carbon->parse($date);
    $cache_keys = [
      'api.expenses.get.'.$app->auth->id.'.'.$date->year.'.'.$date->month,
      'api.totals.'.$app->auth->id.'.'.$date->year.'.'.$date->month,
      'api.exp.tags'.$app->auth->id.'.'.$date->year.'.'.$date->month.'.'.'with_detail',
      'api.exp.tags'.$app->auth->id.'.'.$date->year.'.'.$date->month.'.'.'with_out_detail'
    ];
    $app->cache->deleteMultiple($cache_keys);
  }

  public static function clearIncRouteCache($app,$date){
    // clear cache
    $date = $app->Carbon->parse($date);
    $cache_keys = [
      'api.incomes.get.'.$app->auth->id.'.'.$date->year.'.'.$date->month,
      'api.totals.'.$app->auth->id.'.'.$date->year.'.'.$date->month
    ];
    $app->cache->deleteMultiple($cache_keys);
  }

  public static function fixMoneyInput($money){
    $money = str_replace( ',', '',$money);
    $money = str_replace('$','',$money);
    return floatval($money);
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
      $container->auth = $User->find($expense->user_id);
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
    $app->auth = $app->User->find($data->user_id);
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
    $repeat = isset($repeat) ? in_array($data->repeat, $repeatOptions) ? $data->repeat : null : null;
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
    if (isset($data->tags)) {
      foreach ($data->tags as $tag_id) {
        $tags_data = [
          'exp_id' => $exp_id,
          'tag_id' => $tag_id
        ];
        $app->ExpTags->save($tags_data);
      }
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
  public static function readFile($app,$path="/app/assets/imports/transactions.csv"){
    $reader = Reader::createFromPath($path);
    $reader->setHeaderOffset(0);
    $records = $reader->getRecords($reader->getHeader());
    $expenses = [];
    foreach ($records as $offset => $record) {
      $record = (object) $record;
      $description = explode("-",$record->Description);
      if (!empty($record->Debit)) {
        $data = [
          "name" => ucwords(strtolower(trim($description[0]))),
          "date" => $app->Carbon->createFromFormat('d/m/Y',trim($record->Date))->toDateString(),
          "cost" => floatval($record->Debit) * -1,
          "user_id" => $app->auth->id
        ];
        array_push($expenses,$data);
      }
    }
    return $expenses;
  }
  public static function addFromCsv($app,$path)
  {
    $expenses = self::readFile($app,$path);
    foreach ($expenses as $exp) {
      self::addExpense($app,(object) $exp);
    }
    return $expenses;
  }

  public static function searchLocation($app,$query,$ll="-31.95,115.86")
  {
    if (trim($query) == "") {
      return false;
    }
    $cache = $app->cache;
    $cache_key = 'api.places.'.$ll.'.'.$query;
    $data = [];
    if (!$cache->has($cache_key)) {
      $places = $app->Places->exists($query);
      if ($places) {
        $data = $places;
        $cache->set($cache_key,$data);
        return $data;
      } else {
        $api_endpoint = "https://api.foursquare.com/v2/venues/search?";
        $search_url = "{$api_endpoint}ll={$ll}&query={$query}&client_id=".CLIENT_ID."&client_secret=".CLIENT_SECRET."&v=20170812&radius=100000";
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $search_url);
        if ($res->getStatusCode()==200) {
          $place = $app->Places;
          $place->query = $query;
          $place->response = $res->getBody();
          $place->save();
          $places = $app->Places->exists($query);
          $cache->set($cache_key,$places);
          return $places;
        } else {
          return false;
        }
      }
    } else {
      $data = $cache->get($cache_key);
      return $data;
    }
  }

  public static function locationFromCsv($app,$description)
  {
    if (sizeof($description) > 2) {
      $longData= explode(" ",trim($description[2]));
      $location = "";
      if(sizeof($longData) > 2){
        for ($i=2; $i < sizeof($longData) ; $i++) {
          if ($longData[$i] != "Date") {
            $location .= $longData[$i]." ";
          } else {
            break;
          }
        }
      }
      $location = self::searchLocation($app,$location);
    }
  }

  public static function relatedTags($app,$start=null,$end=null)
  {
    if (!isset($start) && !isset($end)) {
      $start = $Carbon->now()->month(1)->day(1)->toDateString();
      $end = $Carbon->now()->toDateString();
    }

    $db = \Tazzy\Database\DB::connect();
    $qb = new \Tazzy\Database\QueryBuilder();
    $sql = $qb->fields('expenses',['id','name','user_id','date'])
                ->whereBtwn('date',[$start,$end])
                ->andWhere("user_id","=",$app->auth->id)
                ->get();
    $expenses = $db->query($sql)->result();
    foreach ($expenses as $exp) {
      if (!$exp->user_id) {
        continue;
      }
      if (!$app->ExpensesAndTags->hasTag($exp->id)) {
        $availableTags = $app->ExpensesAndTags->getRelatedTags($exp->name);
        if ($availableTags->isNotEmpty()) {
          foreach ($availableTags as $avail) {
            $tags_data = [
              'exp_id' => $exp->id,
              'tag_id' => $avail->tag
            ];
            $app->ExpTags->save($tags_data);
            if (!$app->auth) {
              $app->auth = $app->User->find($exp->user_id);
            }
            self::clearExpRouteCache($app,$exp->date);
            break;
          }
        }
      }
    }

  }

  public static function runRecurringExpense($container,$expenses,$today=null)
  {
    try {
      $Carbon = $container->Carbon;
      $today = isset($today) ? $today : $Carbon->now()->hour(0)->minute(0)->second(0);

      $Exp = $container->Exp;
      $User = $container->User;
      $ExpTags = $container->ExpTags;

      echo "[ ".$today->toDayDateTimeString(). " ] ".sizeof($expenses) . " recurring expenses found\n";
      foreach ($expenses as $exp) {
        $expense = $exp->expense();
        $container->auth = $User->find($expense->user_id);
        $exp_data = null;
        switch ($exp->repeat) {
          case '30':
            $repeat = $Carbon->parse($expense->date)->addMonths(1 * $exp->interval)->toDateString();
            break;
          case '365':
            $repeat = $Carbon->parse($expense->date)->addYears(1 * $exp->interval)->toDateString();
            break;
          case '14':
            $repeat = $Carbon->parse($expense->date)->addWeeks(2 * $exp->interval)->toDateString();
            break;
          case '7':
            $repeat = $Carbon->parse($expense->date)->addWeeks(1 * $exp->interval)->toDateString();
            break;
          case '1':
            $repeat = $Carbon->parse($expense->date)->addDays(1 * $exp->interval)->toDateString();
            break;
        }
        if ($today->eq($Carbon->parse($repeat))) {
          echo "adding expense ".$expense->name." for ".$container->auth->username."\n";
          if (isset($exp->end_repeat)) {
            $end = $Carbon->parse($exp->end_repeat);
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
            $exp_id = $Exp->save($exp_data);
            $exp->interval = $exp->interval+1;
            $exp->save();

            foreach ($expense->expense_tags as $T) {
                $tags_data = [
                  'exp_id' => $exp_id,
                  'tag_id' => $T->tags->id
                ];
                $tags_id = $ExpTags->save($tags_data);
            }
            $loc=\HTTP\Models\Location::where('exp_id',$expense->id)->first();
            if (isset($loc) && $loc->exists) {
              $container->Location->insert([
                'name' => $loc->name,
                'lat' => $loc->lat,
                'long' => $loc->long,
                'exp_id' => $exp_id
              ]);
            }
            \HTTP\Helpers\Utils::clearExpRouteCache($container,$repeat);
            echo $Carbon->now()->toDayDateTimeString()." expense ".$expense->name." saved...\n";
          }
        }
      }
    } catch (\Exception $e){
      $container->log->debug($e->getMessage(),$e->getTrace());
      throw new \Exception($e->getMessage(), 1);
    }
  }

  public static function nomalize($container, $data = [])
  {
      $data = [
        "user_id"   => $container->auth->id,
        "nomalize"  => [
          'coles'   => 'coles',
          'petrol'  => 'petrol',
          'nandos'  => 'nandos',
          'puma'    => 'petrol',
          'parking' => 'parking'

        ]
      ];
      $container->queue->push(\HTTP\Jobs\Handlers\NomalizeHandler::class,$data);
  }
}


?>
