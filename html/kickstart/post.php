<?php
function write_post(& $ksfile, $accel, $bench_type){
  $ip_server = $_SERVER['SERVER_ADDR'];
  $ksfile .= "
%end

%post
#/etc/init.d/sshd start
cd /root
wget http://192.168.1.250/scripts/install_rpmforge.sh &> install_rpmforge.log
bash install_rpmforge.sh &>> install_rpmforge.log
  ";
  if(!strcmp($accel,"cuda")){
    $ksfile .= "
wget http://$ip_server/scripts/install_cuda_sdk.sh &> install.cuda.log
bash install_cuda_sdk.sh &>> install.cuda.log
    ";
  }
  if(!strcmp($bench_type,"on")){
    $ksfile .= "
wget http://$ip_server/scripts/make_bench_shoc.sh &> shoc.bench.log
bash make_bench_shoc.sh &>> shoc.bench.log
wget http://$ip_server/scripts/make_bench_hoomd.sh &> hoomd.bench.log
bash make_bench_hoomd.sh &>> hoomd.bench.log
  ";
  $ksfile .= "
%end
  ";
  };
}
?>
