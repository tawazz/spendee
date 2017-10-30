<?php
  namespace HTTP\Migrations;
  use Phinx\Migration\AbstractMigration;
  use Illuminate\Database\Capsule\Manager as Capsule;
  // Set the event dispatcher used by Eloquent models... (optional)
  use Illuminate\Events\Dispatcher;
  use Illuminate\Container\Container;

  require_once __DIR__.'/../../../app/config/settings.php';


  /**
   * base migration class
   */
  class Migration extends AbstractMigration
  {
    public $capsule;
    public $schema;

    public function init()
    {
       $this->capsule = new Capsule;
       $this->capsule->addConnection([
           'driver'    => \Settings::get('db.driver'),
           'host'      => \Settings::get('db.host'),
           'database'  => \Settings::get('db.db'),
           'username'  => \Settings::get('db.username'),
           'password'  => \Settings::get('db.password'),
           'charset'   => 'utf8',
           'collation' => 'utf8_unicode_ci',
           'prefix'    => \Settings::get('db.prefix'),
       ]);

       $this->capsule->setEventDispatcher(new Dispatcher(new Container));

       // Make this Capsule instance available globally via static methods... (optional)
       $this->capsule->setAsGlobal();

       // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
       $this->capsule->bootEloquent();

       $this->schema = $this->capsule->schema();
    }
  }





 ?>
