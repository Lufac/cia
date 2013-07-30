<?php
function valid_kstype($err,& $kstype){
  if (isset($_GET['ks_type'])) {
    $kstype = $_GET['ks_type'];
    $valido = False;
    if(!strcmp($kstype,"base")) $valido=True; 
    if(!strcmp($kstype,"minima")) $valido=True;
    if(!$valido){
      $err->setError("Tipo de instalación invalida ($kstype)",true);
    }
  }else{
    $kstype = base;
  }
}
?>
