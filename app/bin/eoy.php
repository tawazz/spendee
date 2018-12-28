<?php
date_default_timezone_set ('Australia/Perth' );
require_once __DIR__.'/../../vendor/autoload.php';

use \HTTP\Services\ServiceProvider;
use \Slim\App;
use \Slim\Http\Environment;
use \Symfony\Component\Filesystem\Filesystem;
use Nesk\Puphpeteer\Puppeteer;


$app = new App();
$app->environment = Environment::mock([
    'REQUEST_URI' => '/'
]);
$container = $app->getContainer();
$container->register(new ServiceProvider());



$puppeteer = new Puppeteer;
$browser = $puppeteer->launch(['headless'=>true,
        'args' => ['--no-sandbox', '--disable-setuid-sandbox']
]);

$page = $browser->newPage();
$page->setViewport([
  'width' => 1024,
  'height' => 768,
  'deviceScaleFactor' => 2
]);
$page->goto('http://localhost/eoy');
$page->screenshot(['path' => '/app/assets/eoy.jpg']);

$browser->close();

$user = $container->User->find(7);
$start_date = $container->Carbon->now(new \DateTimeZone('Australia/Perth'))->month(1)->day(1)->toDateString();
$end_date = $container->Carbon->now(new \DateTimeZone('Australia/Perth'))->month(12)->day(31)->toDateString();
$totalexp = $container->Exp->read($user->id)->totalExp($start_date, $end_date);
$totalinc = $container->Inc->read($user->id)->totalInc($start_date, $end_date);
$tags = $container->Helper->getExpenseTagsBetweenDates($container,$user->id,$start_date, $end_date);
if(sizeof($tags) > 0) {
    $data = [
        'name'    => "$user->firstname $user->lastname",
        'email'   => "$user->email",
        'tags'    => $tags,
        'totalInc' => $totalinc,
        'totalExp'=> $totalexp,
        'img' => '/app/assets/eoy.jpg'
    ];
}
$mailer = $container['mailer'];
$sent = $mailer->send('emails/eoy_report.twig',$data,function($message) use($data, $user){
  echo "Sending email...\n";
  $message->to($user->email);
  $message->subject('end of year');
  $message->mailer()->AddEmbeddedImage('/app/assets/eoy.jpg', 'eoy');
});
if(!$sent){
  throw new \Exception($mailer->errors, 1);
}else{
  echo "email sent ...\n";
}


 ?>
