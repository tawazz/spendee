<?php
  require_once __DIR__.'/shell.php';


use BackupManager\Config\Config;
use BackupManager\Filesystems;
use BackupManager\Databases;
use BackupManager\Compressors;
use BackupManager\Manager;
use BackupManager\Filesystems\Destination;
use BackupManager\Tasks;
// build providers
echo "backing up ...\n";
$filesystems = new Filesystems\FilesystemProvider(new Config($container->Config->get('storage')));
$filesystems->add(new HTTP\Helpers\AWSFS);
$filesystems->add(new Filesystems\LocalFilesystem);
$databases = new Databases\DatabaseProvider(new Config(['dev' => $container->Config->get('db')]));
$databases->add(new Databases\MysqlDatabase);
$compressors = new Compressors\CompressorProvider;
$compressors->add(new Compressors\GzipCompressor);
$compressors->add(new Compressors\NullCompressor);
$dt = $container->Carbon->now();
// build manager
$manager =  new Manager($filesystems, $databases, $compressors);
$manager->makeBackup()->run('dev', [
  new Destination('local', 'backup.sql'),
  new Destination('s3', "database/spendee_db_{$dt->toDateTimeString()}.sql")
], 'gzip');
unlink('/backup.sql.gz')
 ?>
