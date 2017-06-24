<?php
require_once __DIR__.'/settings.php';
return [
   'paths' => [
      'migrations' => __DIR__.'/../../migrations',
      'seeds' => __DIR__.'/../../seeds'
   ],
  'migration_base_class' => '\HTTP\Migrations\Migration',
  'environments' => [
    'default_migration_table' => 'phinxlog',
    'default_database' => 'dev',
    'dev' => [
      'name'      => Settings::get('db.db'),
      'adapter'   => Settings::get('db.driver'),
      'host'      => Settings::get('db.host'),
      'user'  => Settings::get('db.username'),
      'pass'  => Settings::get('db.password'),
      'table_prefix' => Settings::get('db.prefix')
    ]
  ]
];

?>
