<?php
  /**
   *
   */
  class Mailer{
    protected $mailer;
    protected $view;
    protected $errors;
    function __construct($view,$mailer)
    {
      $this->mailer = $mailer;
      $this->view = $view;
    }
    public function send($template,$data,$callback){
      $this->errors = null;
      $message = new Message($this->mailer);
      $this->view->appendData($data);
      $message->body($this->view->render($template));

      call_user_func($callback,$message);

      if(!$this->mailer->send()){
        $this->errors = $this->mailer->ErrorInfo;
        return FALSE;
      }else{
        return TRUE;
      }
    }
    public function errors(){
      return $this->errors;
    }
  }

 ?>
