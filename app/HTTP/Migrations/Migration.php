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
           'driver'    => \Settings::get('mysql.driver'),
           'host'      => \Settings::get('mysql.host'),
           'database'  => \Settings::get('mysql.db'),
           'username'  => \Settings::get('mysql.username'),
           'password'  => \Settings::get('mysql.password'),
           'charset'   => 'utf8',
           'collation' => 'utf8_unicode_ci',
           'prefix'    => \Settings::get('mysql.prefix'),
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
