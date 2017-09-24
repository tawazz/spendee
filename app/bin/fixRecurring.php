<?php
require_once __DIR__.'/shell.php';

$Carbon = $container->Carbon;

$Exp = $container->Exp;
$User = $container->User;
$ExpTags = $container->ExpTags;

$expenses = $container->RecurringExpense->where('ended',0)->get();
echo "[ ".$Carbon->now()->toDayDateTimeString(). " ] ".sizeof($expenses) . " recurring expenses found\n";
foreach ($expenses as $exp) {
  $today = $Carbon->now()->hour(0)->minute(0)->second(0);
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

  $repeatDate = $Carbon->parse($repeat);
  if ($repeatDate->lt($today)) {
    echo "[ ".$repeatDate->toDayDateTimeString(). " ] \n";
    $behind = ($today->diffInDays($repeatDate) != null) ? $today->diffInDays($repeatDate) : 0;
    dump($behind);
    for ($i=($behind+1); $i > 0; $i--) {
      \HTTP\Helpers\Utils::runRecurringExpense($container,[$exp],$repeatDate);
      $repeatDate->addDay();
    }

  }
}
 ?>
