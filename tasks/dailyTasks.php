<?php
  use Crunz\Schedule;

  $schedule = new Schedule();
  $schedule->run('php /app/app/bin/recurringExp.php')
    ->daily()
    ->timezone('Australia/Perth')
    ->description('recurring expenses');
  $schedule->run('php /app/app/bin/notifications.php')
    ->dailyAt('06:00')
    ->timezone('Australia/Perth');
  $schedule->run('php /app/app/bin/backup.php')
    ->dailyAt('02:00')
    ->timezone('Australia/Perth');

  return $schedule;
 ?>
