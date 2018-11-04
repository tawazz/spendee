<?php
  namespace HTTP\Jobs\Handlers;
  use HTTP\Jobs\Handlers\BaseHandler;

  /**
   * WeeklyEmailHandler
   */
  class WeeklyEmailHandler extends BaseHandler
  {
    public function fire($job, $data)
    {
      $mailer = $this->container['mailer'];
      $sent = $mailer->send('emails/weekly_report.twig',$data,function($message) use($data){
        echo "Sending email...\n";
        $message->to($data['email']);
        $message->subject('Weekly Report');
      });
      if(!$sent){
        throw new \Exception($mailer->errors, 1);
      }else{
        echo "email sent ...\n";
      }
    }

  }

 ?>
