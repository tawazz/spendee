<?php
  namespace HTTP\Services;
  use Pimple\Container;
  #use HTTP\Helpers\Container;
  use Pimple\ServiceProviderInterface;
  require_once __DIR__.'/../../../Tazzy-Helpers/autoload.php';
  require_once __DIR__.'/../../config/settings.php';
  /**
   * Services
   */
  class ServiceProvider implements ServiceProviderInterface
  {
    public function register(Container $container)
    {
      require_once __DIR__.'/../Models/Models.php';

      $container['session'] = function(){
        return  new \Session();
      };
      $container['hash'] = function(){
        return  new \Hash();
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

      $container["view"] = function($app){
        $view = new \HTTP\Helpers\Twig($app['Config']->get('views.dir'), [
            'cache' => $app['Config']->get('views.cache')
        ]);
        // Instantiate and add Slim specific extension
        $basePath = rtrim(str_ireplace('index.php', '', $app['request']->getUri()->getBasePath()), '/');
        $view->addExtension(new \HTTP\Helpers\TwigExtension($app['router'], $basePath));
        $view->addExtension(new \Twig_Extension_Debug());

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
        $mailer = new \PHPMailer();
        $mailer->IsSMTP();
        $mailer->Host = $app['Config']->get('email.host');
        $mailer->SMTPAuth = $app['Config']->get('email.auth');
        $mailer->SMTPSecure = $app['Config']->get('email.secure');
        $mailer->Port = $app['Config']->get('email.port');
        $mailer->Username = $app['Config']->get('email.user');
        $mailer->Password = $app['Config']->get('email.password');
        $mailer->setFrom($app['Config']->get('email.user'), 'WEBCHEAP');
        $mailer->isHTML(true);
        // TODO: figure out how to use Slim Dependancies in terminal
        return $mailer;
        #return new \Mailer($app['view'],$mailer,$app['queue']);
      };
    }
  }


 ?>
