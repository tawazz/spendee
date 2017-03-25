<?php
use Illuminate\Database\Capsule\Manager as Capsule;
require_once __DIR__.'/../../app/config/settings.php';
$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => Settings::get('mysql.driver'),
    'host'      => Settings::get('mysql.host'),
    'database'  => Settings::get('mysql.db'),
    'username'  => Settings::get('mysql.username'),
    'password'  => Settings::get('mysql.password'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => Settings::get('mysql.prefix'),
]);

// Set the event dispatcher used by Eloquent models... (optional)
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();


 ?>
