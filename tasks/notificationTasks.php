<?php
  require_once __DIR__.'/../vendor/autoload.php';
  use Crunz\Schedule;

  $schedule = new Schedule();
  $schedule->run('php /app/app/bin/notifications.php')
  ->dailyAt('06:00')
  ->timezone('Australia/Perth');
  return $schedule;
 ?>
