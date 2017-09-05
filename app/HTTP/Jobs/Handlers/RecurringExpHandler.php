<?php
  namespace HTTP\Jobs\Handlers;
  use \HTTP\Jobs\Handlers\BaseHandler;
  date_default_timezone_set('Australia/Perth');
  /**
   * RecurringExpenseHandler
   */
  class RecurringExpHandler extends BaseHandler
  {
    public function fire($job, $data)
    {
      try {
        $container = $this->container;
        $Carbon = $container['Carbon'];
        $container->Carbon = $Carbon;
        $today = $Carbon->now()->hour(0)->minute(0)->second(0);

        $Exp = $container->Exp;
        $User = $container->User;
        $ExpTags = $container->ExpTags;

        $expenses = $container->RecurringExpense->where('ended',false)->get();
        echo "[ ".$Carbon->now()->toDayDateTimeString(). " ] ".sizeof($expenses) . " recurring expenses found\n";
        foreach ($expenses as $exp) {
          $expense = $exp->expense();
          $container->auth = $User->get($expense->user_id);
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
  }

 ?>
