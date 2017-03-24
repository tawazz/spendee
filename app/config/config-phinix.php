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
      'name'      => Settings::get('mysql.db'),
      'adapter'   => Settings::get('mysql.driver'),
      'host'      => Settings::get('mysql.host'),
      'user'  => Settings::get('mysql.username'),
      'pass'  => Settings::get('mysql.password'),
      'table_prefix' => Settings::get('mysql.prefix')
    ]
  ]
];

?>
