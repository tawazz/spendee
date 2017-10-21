<?php
  namespace HTTP\Jobs\Handlers;
  use HTTP\Jobs\Handlers\BaseHandler;

  /**
   * RegestrationEmailHandler
   */
  class RegestrationEmailHandler extends BaseHandler
  {
    public function fire($job, $data)
    {
      $mailer = $this->container['mailer'];
      $sent = $mailer->send('emails/regester.twig',$data,function($message) use($data){
        echo "Sending email...\n";
        $message->to($data['to']);
        $message->subject("Activate Spendee Account");
      });
      if(!$sent){
        throw new \Exception($mailer->errors, 1);
      }else{
        echo "email sent ...\n";
      }
    }

  }

 ?>
