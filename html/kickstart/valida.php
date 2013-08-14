<?php

#Las funciones de validacion velifiacn que el parametro sea valiod,
# pero si no esta difinido se define un default

function valid_kstype($err,& $kstype){
  if (isset($_GET['ks_type'])) {
    $kstype = $_GET['ks_type'];
    $valido = False;
    if(!strcmp($kstype,"base")) $valido=True; 
    if(!strcmp($kstype,"minima")) $valido=True;
    if(!$valido){
      $err->setError("Tipo de instalación invalida ($kstype) para kstype");
    }
  }else{
    $kstype = "base";
  }
}

function valid_accel($err,& $accel){
  if (isset($_GET['accel'])) {
    $accel = $_GET['accel'];
    if(!strcmp($accel,"cuda")) return;
    if(!strcmp($accel,"mic")) return;
    if(empty($accel)){
      $err->setError("Parametro vacio para accel");
      return;
    }
    $err->setError("Tipo de parametro invalido ($accel) para accel");
  }else{
    $accel = "none";
  }
}

function valid_storage($err,& $storage){
  if (isset($_GET['storage'])) {
    $storage = $_GET['storage'];
    if(!strcmp($storage,"soft_raid")) return;
    if(!strcmp($storage,"normal")) return;
    if(empty($storage)){
      $err->setError("Parametro vacio para storage");
      return;
    }
    $err->setError("Tipo de parametro invalido ($storage) para storage");
  }else{
    $storage = "normal";
  }
}

function valid_bench($err,& $benchmarks){
  if (isset($_GET['bench'])) {
    $temp_array = explode(",", $_GET['bench']);
    foreach ($temp_array as &$bench_type) {
#      echo $bench_type."\n";
      if(!strcmp($bench_type,"shoc")){
        $benchmarks['shoc'] = "on";
        continue;
      }elseif(!strcmp($bench_type,"hoomd_openmp")){
        $benchmarks['hoomd_openmp'] = "on"; 
        continue;
      }elseif(!strcmp($bench_type,"hoomd_cuda")){
        $benchmarks['hoomd_cuda'] = "on";
        continue;
      }else
        $err->setError("Tipo de parametro invalido ($bench_type) para bench");
    }
  }else{
    $benchmarks = "none";
  }
#  print_r ($benchmarks);
}

?>
