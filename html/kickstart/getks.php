<?php 

include_once "./Logging.php";
include_once "./packages.php";
include_once "./post.php";
include_once "./valida.php";
include_once "./error.php";
include_once "./partition.php";

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

function write_network(& $ksfile, $gw,$host){
$ip_server = $_SERVER['SERVER_ADDR'];
$ip_node = $_SERVER['REMOTE_ADDR'];
$netmask = ip_netmask($ip_node);
$ksfile .= "
url --url http://$ip_server/centos6
network --bootproto=static --device=eth0 --ip=$ip_node --netmask=$netmask --gateway=$gw --nameserver=8.8.8.8 --hostname $host
";
}

function write_bootloader(& $ksfile, $accel){
  if(!strcmp($accel,"cuda")){
    $ksfile .= "
bootloader --location=mbr --append=\"elevator=deadline nomodeset rdblacklist=nouveau nouveau.modeset=0 console=ttyS1,115200 console=tty0\"
    ";
  }else{
    $ksfile .= "
bootloader --location=mbr --append=\"elevator=deadline nomodeset console=ttyS1,115200 console=tty0\"
  ";
  };
}

function write_services(& $ksfile){
    $ksfile .= "
services --disabled openct,libvirtd,ksm,ksmtuned,ibacm,certmonger,bluetooth,cgconfig,crond,irqbalance,cups,iptables,ip6tables,postfix,abrtd,kdump,NetworkManager,cachefilesd,cups,fcoe,iscsi,iscsid,libvirt-guests,portreserve,stap-server,abrt-ccpp,abrt-oops,libvirt-guests,lldpad,pcscd,rpcgssd,opensmd,rdma
services --enabled rsh,rlogin,ntpd,cpuspeed,ipmi
    ";
}

header("Content-Type: text/plain");
//Read Install Parameters
$errores = new Error_cia();
valid_kstype($errores,$ks_type);
if (isset($_GET['accel'])) {
  $accel = $_GET['accel'];
} else {
  $accel = "none";
}
if (isset($_GET['storage'])) {
  $storage = $_GET['storage'];
} else {
  $storage = "none";
}
if (isset($_GET['bench'])) {
  $bench = $_GET['bench'];
} else {
  $bench = "none";
}

//Network parameters
$ip_server = $_SERVER['SERVER_ADDR'];
$ip_node = $_SERVER['REMOTE_ADDR'];
$netmask = ip_netmask($ip_node);
if (isset($_GET['gw'])) {
  $gw = $_GET['gw'];
} else {
  $gw = $ip_server;
}
if (isset($_GET['hostname'])) {
  $hostname = $_GET['hostname'];
} else {
  $hostname = "master"; 
}

$log = new Logging();
$log->lfile('/tmp/mylog.txt');
$log->lwrite("Generando kickstart para: $ip_node");
if ( !$errores->getErrorFlag() ) {
  $ksfile = "#### CIA installation Kickstart (ch3m)  #####\n";
  $ksfile .= "#### Kickstart type: $ks_type\n";
  $ksfile .= "#### Storage type: $storage\n";
  $ksfile .= "#### Accelerator type: $accel\n";
  $ksfile .= "#### Benchmark type: $bench\n";
  $ksfile .= "#### Hostname: $hostname\n"; 
  $ksfile .= "#### Gateway: $gw\n"; 

  write_general_options($ksfile);
  write_network($ksfile, $gw, $hostname);
  write_bootloader($ksfile, $accel);
  write_partioning($ksfile, $storage);
  write_services($ksfile);
  write_packages($ksfile,$ks_type); 
  write_post($ksfile, $accel, $bench);
}else{
  $errores->print_error();
}
echo $ksfile;
$log->lwrite($ksfile);
?>
