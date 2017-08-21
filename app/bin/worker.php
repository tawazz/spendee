<?php

require_once __DIR__.'/../../vendor/autoload.php';

use Illuminate\Queue\Worker;
use HTTP\Jobs\DebugException;
use HTTP\Services\ServiceProvider;
use Pimple\Container as App;

$app = new App();
$app->register(new ServiceProvider());
$queue = $app['queue'];

$dispatcher = new Illuminate\Events\Dispatcher();
$exceptionHandler = new DebugException();
$connection = $queue->getConnection('default');
$tube = 'default';
$worker = new Worker($queue->getQueueManager(), $dispatcher , $exceptionHandler);

while (true) {
    try {
        $job = $connection->pop($tube);
        // Job processed
        if (isset($job)) {
          $job->fire();
          $job->delete();
        }else {
          sleep(5);
        }
    } catch (\Pheanstalk\Exception\ConnectionException $e) {
      echo $e->__toString();
      sleep(5);
    } catch (Exception $e) {
      echo $e->__toString();
      $job->release(60);
      sleep(5);
    } catch (Throwable $e) {
      echo $e->__toString();
      $job->release(60);
      sleep(5);
    }
}
