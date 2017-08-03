<?php
  namespace HTTP\Jobs\Handlers;
  use HTTP\Jobs\Handlers\BaseHandler;

  /**
   * EmailHandler
   */
  class EmailHandler extends BaseHandler
  {
    public function fire($job, $data)
       {
         $mailer = $this->container['mailer'];
         $mailer->addAddress($data['to']);
         $mailer->Subject = $data['subject'];
         $mailer->Body = $data['body'];
         echo "Sending email...\n";
         if(!$mailer->send()){
             throw new Exception($mailer->ErrorInfo, 1);
         }else{
           echo "email sent ...\n";
         }
       }

  }

 ?>
