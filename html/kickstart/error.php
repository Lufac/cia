<?php

class Error_cia{
  private $error_str = "Sin error";
  private $error_flag = False;
  private $error_count = 1;
  
  public function reset(){
    $this->error_str = "Sin error";
    $this->error_flag = False;
  }

  public function setError($err_str){
    //si no se ha prendido la bandera de error 
    //entonces se inicializa la cadena de error
    if(!$this->error_flag) $this->error_str = "";
    $this->error_flag = true;
    $this->error_str .= "ERROR(".$this->error_count."):".$err_str."\n";
    $this->error_count++; 
  }

  public function setErrorFlag($err_flag){
    $this->error_flag = $err_flag;
  }

  public function getErrorFlag(){
    return ($this->error_flag);
  }

  public function getErrorStr(){
    $msg = "##########################################\n";
    $msg .= "++++++++++++++++++++++++++++++++++++++++++\n";
    $msg .= $this->error_str;
    $msg .= "++++++++++++++++++++++++++++++++++++++++++\n";
    $msg .= "##########################################\n";
    return ($msg);
  }

  function print_error(){
    $msg = "##########################################\n";
    $msg .= "++++++++++++++++++++++++++++++++++++++++++\n";
    $msg .= $this->error_str;
    $msg .= "++++++++++++++++++++++++++++++++++++++++++\n";
    $msg .= "##########################################\n";
    echo $msg;
  }
}
?>
