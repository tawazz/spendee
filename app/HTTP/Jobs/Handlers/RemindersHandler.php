<?php
  namespace HTTP\Jobs\Handlers;
  use \HTTP\Models\RecurringExpense;
  use \HTTP\Models\User;
  /**
   *
   */
  class Remindershandler extends \HTTP\Jobs\Handlers\BaseHandler
  {
    public function fire($job, $data){

      $container = $this->container;
      $pb = $container['pb'];
      $Carbon =$container['Carbon'];
      $User = new User();
      $today = $Carbon->now()->hour(0)->minute(0)->second(0);

      $expenses = RecurringExpense::where('ended',false)->get();
      $reminders = [];
      foreach ($expenses as $exp) {
        $expense = $exp->expense();
        $container->auth = $User->get($expense->user_id);
        $repeat = null;

        switch ($exp->repeat) {
          case '30':
            $repeat = $Carbon->parse($expense->date)->addMonths(1 * $exp->interval);
            array_push($reminders,$this->getReminder($exp,$repeat,$today));
            break;
          case '365':
            $repeat = $Carbon->parse($expense->date)->addYears(1 * $exp->interval);
            array_push($reminders,$this->getReminder($exp,$repeat,$today ));
            break;
          case '14':
            $repeat = $Carbon->parse($expense->date)->addWeeks(2 * $exp->interval);
            array_push($reminders,$this->getReminder($exp,$repeat,$today ));
            break;
          case '7':
            $repeat = $Carbon->parse($expense->date)->addWeeks(1 * $exp->interval);
            array_push($reminders,$this->getReminder($exp,$repeat,$today));
            break;
          case '1':
            $repeat = $Carbon->parse($expense->date)->addDays(1 * $exp->interval);
            array_push($reminders,$this->getReminder($exp,$repeat,$today));
            break;
        }
      }

      foreach ($reminders as $reminder) {
        $pb->allDevices()->pushNote("Spendee - Recurring Expenses",$reminder);
      }
      $container['log']->info('Notifications sent');
    }

    public function getReminder($exp,$repeat,$today)
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
