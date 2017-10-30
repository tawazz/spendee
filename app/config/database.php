<?php
use Illuminate\Database\Capsule\Manager as Capsule;
require_once __DIR__.'/../../app/config/settings.php';
$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => Settings::get('db.driver'),
    'host'      => Settings::get('db.host'),
    'database'  => Settings::get('db.db'),
    'username'  => Settings::get('db.username'),
    'password'  => Settings::get('db.password'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => Settings::get('db.prefix'),
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
