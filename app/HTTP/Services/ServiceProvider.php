<?php
  namespace HTTP\Services;
  date_default_timezone_set ('Australia/Perth' );
  use \HTTP\Services\ModelService;
  use \HTTP\Services\GoogleService;
  use \Pimple\Container;
  use \Pimple\ServiceProviderInterface;
  require_once __DIR__.'/../../config/settings.php';
  require_once __DIR__.'/../../config/database.php';
  /**
   * Services
   */
  class ServiceProvider implements ServiceProviderInterface
  {
    public function register(Container $container)
    {
      $container->register(new ModelService());
      $container->register(new GoogleService());

      $container['session'] = function(){
        return  new \Tazzy\Utils\Session();
      };
      $container['hash'] = function(){
        return  new \Tazzy\Utils\Hash();
      };

      $container['Config'] = function(){
        return  new \Settings();
      };

      $container['Helper'] = function(){
        return  new \HTTP\Models\Helper();
      };

      $container['Cookie'] = function(){
        return  new \HTTP\Helpers\Cookie();
      };
      $container['flash'] = function () {
        return new \Slim\Flash\Messages();
      };

      $container['csrf'] = function ($c) {
        $csrf = new \Slim\Csrf\Guard;
        $csrf->setPersistentTokenMode(true);
        return $csrf;
      };

      $container["view"] = function($app){
        $view = new \HTTP\Helpers\Twig($app['Config']->get('views.dir'), [
            'cache' => $app['Config']->get('views.cache')
        ]);
        // Instantiate and add Slim specific extension
        $basePath = rtrim(str_ireplace('index.php', '', $app['request']->getUri()->getBasePath()), '/');
        $view->addExtension(new \HTTP\Helpers\TwigExtension($app['router'], $basePath));
        $view->addExtension(new \Twig_Extension_Debug());
        $view->addExtension(new \HTTP\Helpers\CsrfExtension($app['csrf']));

        return $view;
      };
      $container["queue"] = function ($app) {
        $container = new \HTTP\Jobs\Container();
        $queue = new \Illuminate\Queue\Capsule\Manager($container);

        $queue->getContainer()->bind('encrypter', function() {
            return new \Illuminate\Encryption\Encrypter('tawazz');
        });

        $queue->addConnection([
            'driver' => $app['Config']->get('jobs.driver'),
            'host' => $app['Config']->get('jobs.host'),
            'queue' => $app['Config']->get('jobs.queue'),
        ], 'default');

        $queue->setAsGlobal();
        return $queue;
      };

      $container['mailer'] = function ($app){
        $config = $app['Config'];
        $mailer = new \PHPMailer();
        $mailer->IsSMTP();
        $mailer->Host = $app['Config']->get('email.host');
        $mailer->Port = $app['Config']->get('email.port');
        $mailer->Username = $app['Config']->get('email.user');
        $mailer->Password = $app['Config']->get('email.password');
        $mailer->setFrom($app['Config']->get('email.from'), 'Spendee');
        $mailer->isHTML(true);
        $mailer->SMTPDebug = 2;
        if (!$config->get('debug')) {
          $mailer->SMTPSecure = $app['Config']->get('email.secure');
          $mailer->SMTPAuth = $app['Config']->get('email.auth');
          $mailer->SMTPDebug = 0;
        }
        $mailer = new \Tazzy\Utils\Mailer($app['view'],$mailer);
        return $mailer;
      };

      $container['Carbon'] = function ($app){
        return new \Carbon\Carbon();
      };

      $container['log'] = function ($app) {
        $log = new \Monolog\Logger('files');
        $formatter = new \Monolog\Formatter\LineFormatter( null, null, false, true);

        $debugHandler = new \Monolog\Handler\StreamHandler('/app/logs/debug.log',\Monolog\Logger::DEBUG);
        $errorHandler = new \Monolog\Handler\StreamHandler('/app/logs/error.log',\Monolog\Logger::ERROR);
        $taskHandler = new \Monolog\Handler\StreamHandler('/app/logs/tasks.log',\Monolog\Logger::INFO);

        $debugHandler->setFormatter($formatter);
        $errorHandler->setFormatter($formatter);
        $taskHandler->setFormatter($formatter);

        $log->pushHandler($debugHandler);
        $log->pushHandler($errorHandler);
        $log->pushHandler($taskHandler);

        return $log;
      };

      $container['cache'] = function($app) {
        $cache = new \Symfony\Component\Cache\Simple\FilesystemCache();
        return $cache;
      };

      $container['pb'] = function($app) {
        $pb = new \Pushbullet\Pushbullet($app->Config->get('pushbullet.token'));
        return $pb;
      };
      $container['debugbar_middleware'] = function($app) {
        $debugbar = new \DebugBar\StandardDebugBar();
        $debugbarRenderer = $debugbar->getJavascriptRenderer('/phpdebugbar');
        $middleware = new \PhpMiddleware\PhpDebugBar\PhpDebugBarMiddleware($debugbarRenderer);
        return $middleware;
      };

      $container['http'] = function ($c) {
        return new \GuzzleHttp\Client([
          'timeout'  => 2.0,
        ]);
      };
    }
  }


 ?>
