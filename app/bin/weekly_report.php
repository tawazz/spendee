<?php

require_once __DIR__.'/shell.php';
$queue = $container['queue'];

$users = $container->User->all();

foreach($users as $user) {
    $totalexp = $container->Exp->read($user->id)->totalExp("2018-1-1", "2018-10-31");
    $totalinc = $container->Inc->read($user->id)->totalInc("2018-1-1", "2018-10-31");
    $tags = $container->Helper->getExpenseTagsBetweenDates($container,$user->id,"2018-1-1", "2018-10-31");
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



