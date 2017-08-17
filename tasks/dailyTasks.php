<?php
  use Crunz\Schedule;
  date_default_timezone_set('Australia/Perth');

  $schedule = new Schedule();
  $schedule->run('php /app/app/bin/clearOldSessions.php')->daily()->description('clearOldSessions');
  $schedule->run('php /app/app/bin/recurringExp.php')->daily()->description('recurring expenses');

  return $schedule;
 ?>
