<?php 

include_once "./Logging.php";
include_once "./packages.php";
include_once "./post.php";
include_once "./valida.php";
include_once "./error.php";
include_once "./partition.php";
include_once "./options.php";

function write_general_options(){
	$txttmp = "
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
	return $txttmp;
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

function write_network($opt){
	$ip_server = $_SERVER['SERVER_ADDR'];
	$ip_node = $_SERVER['REMOTE_ADDR'];
	$netmask = ip_netmask($ip_node);
	$txttmp = "
url --url http://$ip_server/centos6
network --bootproto=static --device=eth0 --ip=$ip_node --netmask=$netmask --gateway=$opt->gw --nameserver=8.8.8.8 --hostname $opt->hostname
	";
	return $txttmp;
}

function write_bootloader($opt){	
  if(!strcmp($opt->accel,"cuda")){
    $tmpstr = "
bootloader --location=mbr --append=\"elevator=deadline nomodeset rdblacklist=nouveau nouveau.modeset=0 console=ttyS1,115200 console=tty0\"
    ";
  }else{
    $tmpstr = "
bootloader --location=mbr --append=\"elevator=deadline nomodeset console=ttyS1,115200 console=tty0\"
  ";
  };
	return $tmpstr;
}

function write_services(){
    $tmpstr = "
services --disabled openct,libvirtd,ksm,ksmtuned,ibacm,certmonger,bluetooth,cgconfig,crond,irqbalance,cups,iptables,ip6tables,postfix,abrtd,kdump,NetworkManager,cachefilesd,cups,fcoe,iscsi,iscsid,libvirt-guests,portreserve,stap-server,abrt-ccpp,abrt-oops,libvirt-guests,lldpad,pcscd,rpcgssd,opensmd,rdma
services --enabled rsh,rlogin,ntpd,cpuspeed,ipmi
    ";
		return $tmpstr;
}

header("Content-Type: text/plain");
//Read Install Parameters
$errores = new ErrorCIA();
$opciones = new OptionsCIA();
valid_kstype($errores,$opciones);
valid_accel($errores,$opciones);
valid_storage($errores,$opciones);
valid_bench($errores,& $opciones);
valid_ipmi($errores,& $opciones);

//Network parameters
$ip_server = $_SERVER['SERVER_ADDR'];
$ip_node = $_SERVER['REMOTE_ADDR'];
$netmask = ip_netmask($ip_node);
if (isset($_GET['gw'])) {
  $opciones->gw = $_GET['gw'];
} else {
  $opciones->gw = $ip_server;
}
if (isset($_GET['hostname'])) {
  $opciones->hostname = $_GET['hostname'];
} else {
  $opciones->hostname = "master"; 
}

$log = new Logging();
$log->lfile('/tmp/mylog.txt');
$log->lwrite("Generando kickstart para: $ip_node");
if ( !$errores->getErrorFlag() ) {
  $ksfile = $opciones->getOptionsMsg();
  $ksfile .= write_general_options();
  $ksfile .= write_network($opciones);
  $ksfile .= write_bootloader($opciones);
	$ksfile .= write_partioning($opciones);
  $ksfile .= write_services($opciones);
  $ksfile .= write_packages($opciones); 
  $ksfile .= write_post($opciones);
}else{
  $ksfile = $errores->getErrorStr();
}
echo $ksfile;
$log->lwrite($ksfile);
?>
