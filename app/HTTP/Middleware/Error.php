<?php
  namespace HTTP\Middleware;

  /**
   * Error
   */
  class Error extends \HTTP\Middleware\BaseMiddleware
  {

    function __invoke($request,$response,$next)
    {
      $response = $next($request, $response);

      // Check if the response should render a 404
      if (404 === $response->getStatusCode() && 0 === $response->getBody()->getSize())
      {
          // A 404 should be invoked
          $handler = $this->container['notFoundHandler'];
          return $handler($request, $response);
      }

      // Any other request, pass on current response
      return $response;
    }
  }

 ?>
