<?php

class Error_cia{
  private $error_str = "Sin error";
  private $error_flag = False;
  
  public function reset(){
    $this->error_str = "Sin error";
    $this->error_flag = False;
  }

  public function setError($err_str, $err_flag){
    $this->error_str = $err_str;
    $this->error_flag = $err_flag;
  }

  public function getErrorFlag(){
    return ($this->error_flag);
  }

  function print_error(){
    echo "##########################################\n";
    echo "++++++++++++++++++++++++++++++++++++++++++\n";
    echo "ERROR: $this->error_str\n";
    echo "++++++++++++++++++++++++++++++++++++++++++\n";
    echo "##########################################\n";
  }

}
?>
