<?php
  namespace HTTP\Helpers;

  use \League\Flysystem\AwsS3v3\AwsS3Adapter;
  use Aws\S3\S3Client;
  use League\Flysystem\Filesystem as Flysystem;
  use BackupManager\Filesystems;

  class AWSFS extends Filesystems\Awss3Filesystem
  {
    /**
     * @param array $config
     * @return \League\Flysystem\Filesystem
     */
    public function get(array $config) {
        $client = S3Client::factory([
            'credentials' => [
                'key'    => $config['key'],
                'secret' => $config['secret'],
            ],
            'region' => $config['region'],
            'version' => isset($config['version']) ? $config['version'] : 'latest',
            'endpoint' => $config['endpoint'],
            'use_path_style_endpoint' => true,
        ]);
        return new Flysystem(new AwsS3Adapter($client, $config['bucket'], $config['root']));
    }
  }

 ?>
