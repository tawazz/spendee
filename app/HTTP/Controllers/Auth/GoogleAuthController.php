<?php
namespace HTTP\Controllers\Auth;

class GoogleAuthController extends \HTTP\Controllers\BaseController
{

  public function __invoke($req, $resp,$args)
  {
    $googleService = $this->google;
    if (!empty($req->getParam('code'))) {
      // retrieve the CSRF state parameter
      $state = $req->getParam('state') !==null ? $req->getParam('state') : null;
      try {
      // This was a callback request from google, get the token
      $googleService->requestAccessToken($_GET['code'], $state);
      // Send a request with it
      $result = json_decode($googleService->request('userinfo'), true);
      if ($this->authenticate($result['email'],$resp)) {
        return $this->redirect($resp,$this->urlFor('expenses'));
      }
    } catch (\Exception $e) {
        return $this->redirect($resp,'/login/google?go=google');
    }
      $this->flash->addMessage("global","Google Account not regestered");
      return $this->redirect($resp,$this->urlFor('login'));
    } elseif (!empty($req->getParam('go')) && $req->getParam('go') === 'google') {
      $url = $googleService->getAuthorizationUri()->getAbsoluteUri();
      return $this->redirect($resp,$url);
    } else {
      return $this->redirect($this->urlFor('login'));
    }
  }

  private function authenticate($email,$resp){
    $User = $this->User;
    try {
      $user = $User->where('email',$email)->first();
      if($user){
        $this->session->put('id',$user->id);
        $remember_hash = $this->hash->make($this->hash->salt(10));
        $User->remember($user->id,$remember_hash);
        $resp = $this->Cookie->setCookie($resp,'remember',"{$remember_hash}",$this->Carbon::parse('+4 weeks')->timestamp);
        return true;
      }
    } catch (Exception $e) {
      return false;
    }
    return false;
  }
}


?>
