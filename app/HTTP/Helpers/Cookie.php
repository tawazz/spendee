<?php
  namespace HTTP\Helpers;
  use Dflydev\FigCookies\FigRequestCookies;
  use Dflydev\FigCookies\FigResponseCookies;
  use Dflydev\FigCookies\SetCookie;
  use Slim\Http\{Request,Response};
  /**
   * Cookie Helpers
   */
  class Cookie
  {
    public function setCookie(Response $resp,$key,$val,$expires=Null)
    {
      $cookie = SetCookie::create($key)->withValue($val);
      if (isset($expires)) {
          $cookie->withExpires($expires);
          $resp = FigResponseCookies::set($resp,$cookie);
          return $resp;
      }
      $resp = FigResponseCookies::set($resp,$cookie);
      return $resp;
    }

    public function getCookie(Request $req,$value)
    {
        return FigRequestCookies::get($req,$value);
    }

    public function deleteCookie(Response $resp,$value)
    {
        $resp = FigResponseCookies::expire($resp,$value);
        return $resp;
    }
  }

 ?>
