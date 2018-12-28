<?php
  namespace HTTP\Helpers;
  use Tazzy\Utils\Mailer as Mail;
  use HTTP\Helpers\Message;

  /**
   *
   */
  class Mailer extends Mail
  {
    public function send($template,$data,$callback){
      $this->errors = null;
      $message = new Message($this->mailer);
      $message->body($this->view->fetch($template,$data));
      call_user_func($callback,$message);
      if(!$this->mailer->send()){
        $this->errors = $this->mailer->ErrorInfo;
        return FALSE;
      }else{
        $this->mailer->ClearAllRecipients();
        $this->mailer->ClearAttachments();
        return TRUE;
      }
    }
  }


 ?>
