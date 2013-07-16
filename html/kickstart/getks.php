<?php 

if(!@include_once('./Logging.php') ) {
    echo 'can not include';
}

function write_general_options(& $ksfile){
$ksfile .= "
text
install
lang en_US.UTF-8
keyboard us
rootpw ironman
skipx
firewall --disabled
selinux --disabled
authconfig --enableshadow --enablemd5
timezone --utc America/Mexico_City
";
}

function ip_netmask($ip) {
  $pieces = explode(".", $ip);
  if(!strcmp($pieces[0],"192")){
    $mask = "255.255.255.0";
  }else if(!strcmp($pieces[0],"172")){ 
    $mask = "255.255.0.0";
  }
  return "$mask";
}

function write_network(& $ksfile, $ip_server,$ip_node,$netmask,$gw,$host){
$ksfile .= "
url --url http://$ip_server/centos6
network --bootproto=static --device=eth0 --ip=$ip_node --netmask=$netmask --gateway=$gw --nameserver=8.8.8.8 --hostname $host
";
}

function write_bootloader(& $ksfile, $ks_type){
  if(!strcmp($ks_type,"nvidia")){
    $ksfile .= "
bootloader --location=mbr --append=\"elevator=deadline nomodeset rdblacklist=nouveau nouveau.modeset=0 console=ttyS1,115200 console=tty0\"
    ";
  }else{
    $ksfile .= "
bootloader --location=mbr --append=\"elevator=deadline nomodeset console=ttyS1,115200 console=tty0\"
  ";
  };
}

function write_partioning(& $ksfile, $ks_type){
  if(!strcmp($ks_type,"RAID")){
    $ksfile .= "
clearpart --all
part raid.01 --size=10000  --ondisk=sda
part raid.02 --size=10000 --ondisk=sda

part raid.03 --size=10000  --ondisk=sdb
part raid.04 --size=10000 --ondisk=sdb

raid / --level=RAID1 --device=md0 --fstype=ext4 raid.01 raid.03
raid /home  --level=RAID1 --device=md1 --fstype=ext4 raid.02 raid.04
    ";
  }else{
    $ksfile .= "
clearpart --all
part /boot --asprimary --fstype=ext4 --size=100 --bytes-per-inode=4096
part swap --asprimary --fstype=swap --recommended --bytes-per-inode=4096
part / --asprimary --fstype=ext4 --grow --size=10000 --bytes-per-inode=4096
    ";
  };
}

function write_services(& $ksfile){
    $ksfile .= "
services --disabled openct,libvirtd,ksm,ksmtuned,ibacm,certmonger,bluetooth,cgconfig,crond,irqbalance,cups,iptables,ip6tables,postfix,abrtd,kdump,NetworkManager,cachefilesd,cups,fcoe,iscsi,iscsid,libvirt-guests,portreserve,stap-server,abrt-ccpp,abrt-oops,libvirt-guests,lldpad,pcscd,rpcgssd,opensmd,rdma
services --enabled rsh,rlogin,ntpd,cpuspeed,ipmi
    ";
}

function write_packages(& $ksfile){
  $ksfile .= "
install
%packages --ignoremissing
@base
@Development Tools
cmake
zlib-devel
boost-devel
python-devel
  ";
}

function write_post(& $ksfile, $ks_type, $bench_type, $ip_server){
  $ksfile .= "
/etc/init.d/sshd start
cd /root
wget http://192.168.1.250/scripts/install_rpmforge.sh &> install_rpmforge.log
bash install_rpmforge.sh &>> install_rpmforge.log
  ";
  if(!strcmp($ks_type,"nvidia")){
    $ksfile .= "
wget http://$ip_server/scripts/install_cuda_sdk.sh &> install.cuda.log
bash install_cuda_sdk.sh &>> install.cuda.log
    ";
  }
  if(!strcmp($bench_type,"benchmark")){
    $ksfile .= "
wget http://$ip_server/scripts/make_bench_shoc.sh &> shoc.bench.log
bash make_bench_shoc.sh &>> shoc.bench.log
wget http://$ip_server/scripts/make_bench_hoomd.sh &> hoomd.bench.log
bash make_bench_hoomd.sh &>> hoomd.bench.log
  ";
  };
}

header("Content-Type: text/plain");
$ks_type = $_GET["ks_type"];
$ip_server = $_SERVER['SERVER_ADDR'];
$ip_node = $_SERVER['REMOTE_ADDR'];
$netmask = ip_netmask($ip_node);
$gw = $_GET["gw"];
$hostname = $_GET["hostname"];
$ksfile = "####Tipo de kickstart: $ks_type\n";
$bench_type = $_GET["bench"];
$log = new Logging();
$log->lfile('/tmp/mylog.txt');
$log->lwrite("Generando kickstart para: $ip_node");
if ( !strcmp($ks_type,"nvidia") or !strcmp($ks_type,"RAID") or !strcmp($ks_type,"default") ) {
  write_general_options($ksfile);
  write_network($ksfile,$ip_server,$ip_node,$netmask,$gw,$hostname);
  write_bootloader($ksfile,$ks_type);
  write_partioning($ksfile,$ks_type);
  write_services($ksfile);
  write_packages($ksfile); 
  write_post($ksfile,$ks_type, $bench_type, $ip_server);
}else{
  echo "####Error en la generacion del kickstart\n";
}
echo $ksfile;
$log->lwrite($ksfile);
?>
