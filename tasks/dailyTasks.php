<?php
  use Crunz\Schedule;

  $schedule = new Schedule();
  $schedule->run('php /app/app/bin/clearOldSessions.php')->daily()->timezone('Australia/Perth')->description('clearOldSessions');
  $schedule->run('php /app/app/bin/recurringExp.php')->daily()->timezone('Australia/Perth')->description('recurring expenses');
  return $schedule;
 ?>
