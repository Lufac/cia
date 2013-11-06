<?php

#Las funciones de validacion velifiacn que el parametro sea valiod,
# pero si no esta difinido se define un default

function valid_kstype($err, $opt){
  if (isset($_GET['ks_type'])) {
    $temp_var = $_GET['ks_type'];
    if(!strcmp($temp_var ,"base")) $valido=True;
    if(!strcmp($temp_var ,"minima")) $valido=True;
		if(!strcmp($temp_var ,"master")) $valido=True;
    if($valido){
      $opt->ks_type = $temp_var;
			return;
    }else
			$err->setError("Tipo de instalación invalida ($temp_var ) para kstype");
  }else{
    $err->setError("Ningun tipo de instalación definida");
  }
}

function valid_accel($err, $opt){
  if (isset($_GET['accel'])) {
		$temp_array = explode(",", $_GET['accel']);
		
		if(!strcmp($temp_array[0] ,"cuda")){
			$opt->accel = "cuda";
			if (isset($temp_array[1])){
				if(!strcmp($temp_array[1] ,"yum"))
					$opt->accel_type_install = "yum";
				else
					$opt->accel_type_install = "local";
			}else{
				$err->setError("Tipo de instalacion de cuda invalida (local, yum)");
			}
			return;
		}
		if(!strcmp($temp_array[0] ,"mic")){
			$opt->accel = "mic";
			return;
		}
		if(empty($temp_array[0])){
      $err->setError("Parametro vacio para accel");
      return;
    }
		$err->setError("Tipo de parametro invalido ($temp_array[0]) para accel");
  }else{
    $opt->accel = "none";
		$opt->accel_type_install = "none";
  }
}

function valid_storage($err,& $opt){
  if (isset($_GET['storage'])) {
    $opt->storage = $_GET['storage'];
    if(!strcmp($opt->storage,"soft_raid")) return;
    if(!strcmp($opt->storage,"normal")) return;
    if(empty($opt->storage)){
      $err->setError("Parametro vacio para storage");
      return;
    }
    $err->setError("Tipo de parametro invalido ($opt->storage) para storage");
  }else{
    $opt->storage = "normal";
  }
}

function valid_bench($err,$opt){
  if (isset($_GET['bench'])) {
    $temp_array = explode(",", $_GET['bench']);
    foreach ($temp_array as &$bench_type) {
#      echo $bench_type."\n";
      if(!strcmp($bench_type,"shoc")){
        $opt->benchmarks_list['shoc'] = "on";
        continue;
      }elseif(!strcmp($bench_type,"hoomd_openmp")){
        $opt->benchmarks_list['hoomd_openmp'] = "on"; 
        continue;
      }elseif(!strcmp($bench_type,"hoomd_cuda")){
        $opt->benchmarks_list['hoomd_cuda'] = "on";
        continue;
			}elseif(!strcmp($bench_type,"bonnie")){
        $opt->benchmarks_list['bonnie'] = "on";
        continue;
			}elseif(!strcmp($bench_type,"stream")){
        $opt->benchmarks_list['stream'] = "on";
        continue;
      }else
        $err->setError("Tipo de parametro invalido ($bench_type) para bench");
    }
  }
}

function valid_ipmi($err,$opt){
  if (isset($_GET['ipmi'])) {
    $temp_array = explode(",", $_GET['ipmi']);
    foreach ($temp_array as &$ipmi_option) {
      if(!strcmp($ipmi_option,"dhcp")){
        $opt->ipmi_list['dhcp'] = "on";
        continue;
      }else{
        $err->setError("Tipo de parametro invalido ($ipmi_option) para ipmi");
			}
    }
  }
}

function valid_distro($err,& $opt){
  if (isset($_GET['distro'])) {
    $opt->distro = $_GET['distro'];
    if(!strcmp($opt->distro,"centos6/x86_64")) return;
		if(!strcmp($opt->distro,"centos5")) return;
    $err->setError("Tipo de parametro invalido ($opt->distro) para storage");
  }else{
		#Sin variable por default la distro es centos6
    $opt->distro = "centos6";
  }
}

?>
