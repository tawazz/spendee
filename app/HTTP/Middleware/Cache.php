<?php
  namespace HTTP\Middleware;

  use \Tazzy\Utils\Hash;
  use \Slim\HttpCache\CacheProvider;
  /**
   * Cache
   */
  class Cache extends \HTTP\Middleware\BaseMiddleware
  {

    function __invoke($request,$response,$next)
    {
      $cache = new CacheProvider();
      $response = $cache->withExpires($response, 86400);
      $response = $next($request, $response);
      return $response;
    }
  }

 ?>
