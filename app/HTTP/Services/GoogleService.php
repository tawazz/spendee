<?php
  namespace HTTP\Services;

  use \Pimple\Container;
  use \Pimple\ServiceProviderInterface;
  use \OAuth\OAuth2\Service\Google;
  use \OAuth\Common\Storage\Session;
  use \OAuth\Common\Consumer\Credentials;
  use \OAuth\Common\Http\Uri\UriFactory;
  use \OAuth\ServiceFactory;
  /**
   * Models Service
   */
  class GoogleService implements ServiceProviderInterface {

    public function register(Container $container){

      $container['google'] = $container->factory(function($c) {
        $Config = $c['Config'];
        $uriFactory = new UriFactory();
        $serviceFactory = new ServiceFactory();
        
        // Session storage
        $storage = new Session();
        // Setup the credentials for the requests
        $credentials = new Credentials(
          $Config->get('google.key'),
          $Config->get('google.secret'),
          $Config->get('google.callback')
        );
        $googleService = $serviceFactory->createService('google', $credentials, $storage, array('userinfo_email', 'userinfo_profile'));
        return $googleService;
      });
    }
  }
 ?>
