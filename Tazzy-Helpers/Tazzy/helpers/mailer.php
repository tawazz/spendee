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
      $message->body($this->view->fetch($template,$data));

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

    public function __get($prop)
    {
      if($this->{$prop}){
        return $this->{$prop};
      }
      if($this->mailer->{$prop}){
        return $this->{$prop};
      }
      return null;
    }
  }

 ?>
