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
$page->goto('http://localhost/eoy');
$page->screenshot(['path' => '/app/assets/eoy.jpg']);

$browser->close();


 ?>
