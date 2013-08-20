<?php
function write_post($opt){
  $ip_server = $_SERVER['SERVER_ADDR'];
  $tmpstr = "
%end

%post
#/etc/init.d/sshd start
cd /root
mkdir .install_post
cd .install_post

wget http://$ip_server/scripts/install_ch3mfiles.sh &> install_ch3mfiles.log
bash install_ch3mfiles.sh $ip_server &>> install_ch3mfiles.log

wget http://$ip_server/scripts/install_rpmforge.sh &> install_rpmforge.log
bash install_rpmforge.sh &>> install_rpmforge.log

wget http://$ip_server/scripts/save_logs.sh &> save_logs.log
bash save_logs.sh $ip_server &>> save_logs.log
  ";
	
	//INSTALL CIA TOOLS
	if(!strcmp($opt->ks_type,"master"))
    $tmpstr .= "
wget http://$ip_server/scripts/install_cia.sh &> install_cia.log
bash install_cia.sh $ip_server &>> install_cia.log
    ";
  
  //INSTALL CUDA
  if(!strcmp($opt->accel,"cuda"))
    $tmpstr .= "
wget http://$ip_server/scripts/install_cuda_sdk.sh &> install.cuda.log
bash install_cuda_sdk.sh $ip_server $accel_type_install &>> install.cuda.log
    ";

  //BENCHMARKS
  if(isset($opt->benchmarks_list['shoc']))
    $tmpstr .= "
wget http://$ip_server/scripts/make_bench_shoc.sh &> shoc.bench.log
bash make_bench_shoc.sh $ip_server &>> shoc.bench.log
    ";
  if(isset($opt->benchmarks_list['hoomd_openmp']))
    $tmpstr .= "
wget http://$ip_server/scripts/make_bench_hoomd_openmp.sh &> hoomd.bench_openmp.log
bash make_bench_hoomd_openmp.sh $ip_server &>> hoomd.bench_openmp.log
    ";
  if(isset($opt->benchmarks_list['hoomd_cuda']))
    $tmpstr .= "
wget http://$ip_server/scripts/make_bench_hoomd_cuda.sh &> hoomd.bench_cuda.log
bash make_bench_hoomd_cuda.sh $ip_server &>> hoomd.bench_cuda.log
    ";
	if(isset($opt->benchmarks_list['bonnie']))
    $tmpstr .= "
wget http://$ip_server/scripts/make_bench_bonnie.sh &> bonnie.bench.log
bash make_bench_bonnie.sh &>> bonnie.bench.log
    ";
	
	#IPMI
	if(isset($opt->ipmi_list['dhcp']))
    $tmpstr .= "
wget http://$ip_server/scripts/set_ipmi.sh &> set_ipmi.log
bash set_ipmi.sh &>> set_ipmi.log
    ";
  $tmpstr .= "
%end
  ";
	return $tmpstr;
}
?>
