<?php

require_once __DIR__.'/shell.php';
$queue = $container['queue'];

$users = $container->User->all();
$start_date = $container->Carbon->now(new \DateTimeZone('Australia/Perth'))->subWeek()->toDateString();
$end_date = $container->Carbon->now(new \DateTimeZone('Australia/Perth'))->toDateString();
foreach($users as $user) {
    $totalexp = $container->Exp->read($user->id)->totalExp($start_date, $end_date);
    $totalinc = $container->Inc->read($user->id)->totalInc($start_date, $end_date);
    $tags = $container->Helper->getExpenseTagsBetweenDates($container,$user->id,$start_date, $end_date);
    if(sizeof($tags) > 0) {
        $data = [
            'name'    => "$user->firstname $user->lastname",
            'email'   => "$user->email",
            'tags'    => $tags,
            'totalInc' => $totalinc,
            'totalExp'=> $totalexp
        ];
        $queue->push(HTTP\Jobs\Handlers\WeeklyEmailHandler::class,$data);
    }
}



