<?php

#Las funciones de validacion velifiacn que el parametro sea valiod,
# pero si no esta difinido se define un default

function valid_kstype($err,& $kstype){
  if (isset($_GET['ks_type'])) {
    $kstype = $_GET['ks_type'];
    $valido = False;
    if(!strcmp($kstype,"base")) $valido=True; 
    if(!strcmp($kstype,"minima")) $valido=True;
		if(!strcmp($kstype,"master")) $valido=True;
    if(!$valido){
      $err->setError("Tipo de instalación invalida ($kstype) para kstype");
    }
  }else{
    $kstype = "base";
  }
}

function valid_accel($err,& $accel, & $accel_type_install){
  if (isset($_GET['accel'])) {
		$temp_array = explode(",", $_GET['accel']);
		if(!strcmp($temp_array[0] ,"cuda")){
			$accel = "cuda";
			if (isset($temp_array[1])){
				if(!strcmp($temp_array[1] ,"net"))
					$accel_type_install = "net";
				else
					$accel_type_install = "local";
			}
			return;
		}if(!strcmp($temp_array[0] ,"mic")){
			$accel = "mic";
			return;
		}if(empty($temp_array[0])){
      $err->setError("Parametro vacio para accel");
      return;
    }
		$err->setError("Tipo de parametro invalido ($temp_array[0]) para accel");
//    $accel = $_GET['accel'];
//    if(!strcmp($accel,"cuda")) return;
//    if(!strcmp($accel,"mic")) return;
//    if(empty($accel)){
//      $err->setError("Parametro vacio para accel");
//      return;
//    }
//    $err->setError("Tipo de parametro invalido ($accel) para accel");
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
			}elseif(!strcmp($bench_type,"bonnie")){
        $benchmarks['bonnie'] = "on";
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
