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
         $sent = $mailer->send('emails/contact.php',$data,function($message) use($data){
           echo "Sending email...\n";
           $message->to($data['to']);
           $message->subject($data['subject']);
         });
         if(!$sent){
             throw new \Exception($mailer->errors, 1);
         }else{
           echo "email sent ...\n";
         }
       }

  }

 ?>
