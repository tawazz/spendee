<?php
  namespace HTTP\Jobs\Handlers;
  use HTTP\Jobs\Handlers\BaseHandler;

  /**
   * ResetPasswordEmailHandler
   */
  class ResetPasswordEmailHandler extends BaseHandler
  {
    public function fire($job, $data)
    {
      $mailer = $this->container['mailer'];
      $sent = $mailer->send('emails/forgot_password.twig',$data,function($message) use($data){
        echo "Sending email...\n";
        $message->to($data['to']);
        $message->subject("Forgot Spendee Password Changed");
      });
      if(!$sent){
        throw new \Exception($mailer->errors, 1);
      }else{
        echo "email sent ...\n";
      }
    }

  }

 ?>
