<?php
require_once __DIR__.'/../../vendor/autoload.php';
use \Faker\Factory;
use \HTTP\Services\ServiceProvider;
use \Slim\App;
use \Slim\Http\Environment;
use \HTTP\Helpers\Utils;

$app = new App();
$app->environment = Environment::mock([
    'REQUEST_URI' => '/'
]);
$container = $app->getContainer();
$container->register(new ServiceProvider());

$exp = $container->Exp;
$inc = $container->Inc;
$auth = $container->User->find(20);
$container->auth = $auth;
$faker = Factory::create();
$faker->addProvider(new Faker\Provider\DateTime($faker));
for ($i=0; $i < 25; $i++) {
  $exp_data = [
    'name'=> $faker->randomElement($array = array ('food','car','travel','coles','target','hungry jacks','petrol','phone','woolworths')),
    'cost'=> $faker->numberBetween($min = 10, $max = 100),
    'date'=> $faker->dateTimeInInterval($startDate = 'now', $interval = '+ '.$i.' days', $timezone = date_default_timezone_get())->format("Y-m-d"),
    'user_id'=> 20,
    'tags' => [$faker->numberBetween($min = 1, $max = 5)],
  ];
  $exp_data = (object) $exp_data;
  Utils::addExpense($container,$exp_data);
}
 ?>
