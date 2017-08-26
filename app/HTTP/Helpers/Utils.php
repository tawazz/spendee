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
}


 ?>
