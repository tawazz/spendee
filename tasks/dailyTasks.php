<?php
  use Crunz\Schedule;


  $schedule = new Schedule();
  $schedule->run('php /app/app/bin/clearOldSessions.php')->daily()->description('clearOldSessions');
  $schedule->run('php /app/app/bin/recurringExp.php')->everyFiveMinutes()->description('recurring expenses');

  return $schedule;
 ?>
