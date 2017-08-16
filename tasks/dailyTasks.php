<?php
  use Crunz\Schedule;


  $schedule = new Schedule();
  $schedule->run('php /app/app/bin/clearOldSessions.php')->daily()->description('clearOldSessions');
  $schedule->run('php /app/app/bin/recurringExp.php')->daily()->description('recurring expenses');

  return $schedule;
 ?>
