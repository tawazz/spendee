<?php
  use Crunz\Schedule;


  $schedule = new Schedule();
  $schedule->run('php /app/app/bin/clearOldSessions.php')->daily()->description('clearOldSessions');

  return $schedule;
 ?>
