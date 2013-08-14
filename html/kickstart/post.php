<?php
function write_post(& $ksfile, $accel, $benchmarks){
  $ip_server = $_SERVER['SERVER_ADDR'];
  $ksfile .= "
%end

%post
#/etc/init.d/sshd start
cd /root
mkdir .install_post
cd .install_post
wget http://$ip_server/scripts/install_rpmforge.sh &> install_rpmforge.log
bash install_rpmforge.sh &>> install_rpmforge.log
  ";
  
  //INSTALL CUDA
  if(!strcmp($accel,"cuda"))
    $ksfile .= "
wget http://$ip_server/scripts/install_cuda_sdk.sh &> install.cuda.log
bash install_cuda_sdk.sh $ip_server &>> install.cuda.log
    ";

  //BENCHMARKS
  if(isset($benchmarks['shoc']))
    $ksfile .= "
wget http://$ip_server/scripts/make_bench_shoc.sh &> shoc.bench.log
bash make_bench_shoc.sh $ip_server &>> shoc.bench.log
    ";
  if(isset($benchmarks['hoomd_openmp']))
    $ksfile .= "
wget http://$ip_server/scripts/make_bench_hoomd_openmp.sh &> hoomd.bench_openmp.log
bash make_bench_hoomd_openmp.sh $ip_server &>> hoomd.bench_openmp.log
    ";
  if(isset($benchmarks['hoomd_cuda']))
    $ksfile .= "
wget http://$ip_server/scripts/make_bench_hoomd_cuda.sh &> hoomd.bench_cuda.log
bash make_bench_hoomd_cuda.sh $ip_server &>> hoomd.bench_cuda.log
    ";

  $ksfile .= "
%end
  ";
}
?>
