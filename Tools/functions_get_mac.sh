#!/bin/bash -x 
#===================================================================================
#
# FILE: install_functions
#
# USAGE: source install_functions
#
# DESCRIPTION: En este archivo se guardaran todas las funciones de 
#	instalación del CIA
#
# OPTIONS: 
# REQUIREMENTS: 
# BUGS: 
#   1- Validar que en cada funcion todas las variables 
#   2- Validar que los archivos donde se escriba existan
#   3- Por algo el ciclo de cachar la MAC del DHCP no regresa cero
#   4. Personalizar un kickstart
# NOTES: 
# AUTHOR: M. en C. Jose Maria Zamora Fuentes
# COMPANY: Lufac Computacion
# VERSION: 1.0
# CREATED: 2.06.2013 
# REVISION: 2.06.2013
#===================================================================================


function die(){
  msg=$1
  val=$2
  echo "$msg"
  kill -INT $$
}

#=== FUNCTION ================================================================
# NAME: getMacAdress
# DESCRIPTION: getMacAdress
# PARAMETER
#   INSTALL_DEVICE: Interfaz para escuchar eth0
# RETURN
#   MACADDR: Direccion MAC capturada
#===============================================================================
function getMacAdress(){
  which tcpdump
  if [ $? -eq 1  ]  ; then  echo "tcpdump not found ..." && read ; fi
  which dhcpdump
  if [ $? -eq 1  ]  ; then  echo "dhcpdump not found ..." && read ; fi
  rm -f $MAC_FILE
  BOOT_FILE="pxelinux/pxelinux.bin"
  touch /tmp/bad.mac
  GETMAC=1
  while [ $GETMAC != 0 ] ;do
    MACADDR=""
    echo -n "
#** Waiting for MAC on $INSTALL_DEVICE "
    if [ -z $MACADDR ] ; then
#     MACADDR="$(tcpdump -qte -c 1 -i $INSTALL_DEVICE broadcast and port bootpc 2>/dev/null | cut -d' ' -f1)"
#     MACADDR="$(tcpdump -c 1 -i $INSTALL_DEVICE -entl  port bootpc 2>/dev/null | cut -d' ' -f1)"
      VARS=($(tcpdump -lenx -s 1500 -c 1 -i $INSTALL_DEVICE port bootps or port bootpc 2>/dev/null | dhcpdump | grep -e ^CHADDR: -e ^"OPTION:  60"))
      MACADDR=${VARS[1]:0:17}
      VENDOR=${VARS[9]:0:9}
    fi 
    if [ ! -z $MACADDR ] ;then 
      if grep -q $MACADDR /tmp/bad.mac ; then continue; fi 
      echo "* $VENDOR: $MACADDR" 
      echo -n "Continue with this MAC  y/n  [y]: "
      read -n 1 resp
      echo 
      [ "$resp" == y ] || [ "$resp" == Y ] && GETMAC=0
      [ -z $resp ] && GETMAC=0
      [ "$resp" == n ] || [ "$resp" == N ]  && echo $MACADDR >> /tmp/bad.mac && continue
    fi
  done
}
#=== FUNCTION ================================================================
# NAME: getMacAdresCMD
# DESCRIPTION: get MAC adress from user in interactive mode
# PARAMETER
# RETURN 
#   MACADDR: Direccion MAC capturada
#===============================================================================
function getMacAdressCMD(){
  GETMAC=1
  echo "** press'y' key to input MAC address for node $NODE_NAME or  enter to continue "
  echo -n "** wait 5 sec for answer input ..."
  read -t 5 -s -n 1 INPUT
  if [ ! -z $INPUT ]; then
    while : ; do
      echo
      echo -n "** Please input MAC address for $NODE_NAME: "
      read MAC_tmp
      if [ "$(echo $MAC_tmp | egrep "^([0-9a-fA-F]{2}:){5}[0-9a-fA-F]{2}$")" == "" ];then 
        echo "$MAC_tmp is invalid !!" 
        continue
      else  
        MACADDR=$MAC_tmp
      fi
      GETMAC=0
      break
    done
  fi  
}

#=== FUNCTION ================================================================
# NAME: Write PXE 
# DESCRIPTION: writePXE
# PARAMETER
#   MACADDR: Direccion MAC  para escribir PXE
#   TFTP_DIR: Ruta del directorio pxelinux.cfg/
#   MASTER_ARCH: Arquitectura del nodo maestro para guardar el kernel
#   NET_PARAM: Parametros de configuracion del kernel para red
#   BOOT_PARAM: Parametros de Booteo para el kernel 
# RETURN
#===============================================================================
function writePXE(){
  echo "Create... $TFTP_DIR/$PXENAME_FILE"
  [[ ! -d $TFTP_DIR ]] && echo "El directorio de pxelinux.cfg no existe" && return 15
  PXENAME_FILE="01-$(echo $MACADDR | tr : -)"
  echo -e "Procesing... $PXENAME_FILE"
  echo -e "default install
prompt 1
timeout 10
display display.msg

label localboot
        LOCALBOOT 0

label install
  KERNEL ../boot/$MASTER_ARCH/vmlinuz
  APPEND initrd=../boot/$MASTER_ARCH/initrd.img $NET_PARAM $BOOT_PARAM
  IPAPPEND 2 
EOF" > $TFTP_DIR/$PXENAME_FILE
  echo "Create $TFTP_DIR/$PXENAME_FILE... DONE"
}

#=== FUNCTION ================================================================
# NAME: configure_dhcpd
# DESCRIPTION: Este script configura el archivo dhcpd.conf 
# PARAMETER
#   NAME_TARGET: Nombre del nodo objetivo
#   IP_TARGET: IP que recibira el nodo objetivo
#   BOOTFILE: Archivo con el que bootea el nodo objetivo por PXE
#   MACADDR: direccion MAC del nodo objetivo
# RETURN
#===============================================================================
function configure_dhcpd(){
  echo "writing dhcp..."
  write_new_dhcpd  
  VAR=$(egrep "^[#]+Last" /etc/dhcp/dhcpd.conf | wc -l)
  [[ $VAR -ne 1 ]] && die "dhcpd.conf MALFORMED!! several Last entrys" 
  write_dhcpd_entry
  echo "writing dhcp...DONE"
}

function write_dhcpd_entry(){
  [[ -z $NAME_TARGET ]] && die "Write entry in dhcpd.conf: unintialized NAME_TARGET"
  [[ -z $MACADDR ]] && die "Write entry in dhcpd.conf: unintialized MACADDR"
  [[ -z $IP_TARGET ]] && die "Write entry in dhcpd.conf: unintialized IP_TARGET"
  [[ -z $BOOTFILE ]] && die "Write entry in dhcpd.conf: unintialized BOOTFILE"
  VAR=$(echo "$BOOTFILE" | sed 's/\//\\\//')
  sed -i -f - /etc/dhcp/dhcpd.conf <<EOF
/^##*Last/s/.*/    host $NAME_TARGET  { \\
        hardware ethernet $MACADDR; \\
        fixed-address $IP_TARGET; \\
        option host-name \"${NAME_TARGET}\"; \\
        filename \"${VAR}\"; \\
    } \\
\\
######Last Entry /
EOF
}

function write_new_dhcpd(){
  #Overwrite si no esta bien formado el dhcpd.conf
  VAR=$(egrep "^[#]+Last" /etc/dhcp/dhcpd.conf)
  if [ -z "$VAR" ];then
    echo "Create... $DHCP_CONF"
    echo ""
    cp /etc/dhcp/dhcpd.conf /tmp/dhcpd.conf.bad
    echo -e "
#############################################
# dhcpd.conf configuration created by CIA
#
# $(hostname)
# $(date +%A) $(date +%d) $(date +%B) $(date +%Y)
#############################################
ddns-update-style none;
ignore client-updates;

subnet 192.168.1.0 netmask 255.255.255.0 {
        range 192.168.1.10 192.168.1.254;
        default-lease-time 3600;
        max-lease-time 4800;
        option subnet-mask 255.255.255.0;

######Last Entry

}
}" > $DHCP_CONF
  fi
}

function valid_hostname(){
  [[ -z $NAME_TARGET ]] && die "configure_dhcpd var NAME_TARGET unset"
  while : ; do
    VAR=$(egrep "^[[:space:]]+host" /etc/dhcp/dhcpd.conf | awk '{print $2}' | egrep "^$NAME_TARGET$")
    if [ -z "$VAR" ]; then
      break
    fi
    echo "hostname $NAME_TARGET of target in use..."
    echo -n "** Please input hostname:  "
    read NAME_TARGET
  done
  echo "free hostname $NAME_TARGET..."
}

#=== FUNCTION ================================================================
# NAME: start_services
# DESCRIPTION: Este script se encarga de arrancar los servicios y validarlos
# PARAMETER
# RETURN
#===============================================================================
function start_services(){
  echo "Starting services..."
  echo "DHCPDARGS=$INSTALL_DEVICE" > /etc/sysconfig/dhcpd
  if [ -e /etc/rc.d/init.d/dhcpd ] ; then  
    /etc/rc.d/init.d/dhcpd restart 
  else  
    killall dhcpd
    dhcpd -cf $DHCP_CONF $INSTALL_DEVICE 
  fi 
  [ -e /etc/init.d/xinetd ] && /etc/init.d/xinetd restart 
  [ -e /etc/init.d/httpd ] && /etc/init.d/httpd restart 
}
 
#=== FUNCTION ================================================================
# NAME: install_node_master
# DESCRIPTION: writePXE
# PARAMETER
# RETURN
#===============================================================================
function install_node_master(){
  INSTALL_DEVICE="eth0" 
  TARGET_DEVICE="eth0"
  NAME_TARGET="kepler"
  IP_TARGET="192.168.1.210"
  BOOTFILE="pxelinux/pxelinux.0"
  DHCP_CONF="/etc/dhcp/dhcpd.conf"
  GUI_CONF="vnc"
  LOCAL_IP=$(ip addr show eth0 | grep "inet " | awk '{print $2}' | cut -d\/ -f1)
#  VNC_EXTRA="vncconnect=$LOCAL_IP"
  MASTER_ARCH="x86_64"
  GW="192.168.1.1"
  KS_SCHEME="getks.php?ks_type=base&gw=$GW&accel=cuda"
#  repo="repo=http://$LOCAL_IP/isos/$MASTER_ARCH"
  ks="ks=http://$LOCAL_IP/kickstart/$KS_SCHEME"
  BOOT_PARAM="noselinux selinux=0 headless xdriver=vesa nomodeset sshd $repo $ks $GUI_CONF $VNC_EXTRA"
  NET_PARAM="ip=dhcp nicdelay=60 linksleep=60 noipv6 ksdevice=$TARGET_DEVICE"
  TFTP_DIR="/var/lib/tftpboot/pxelinux/pxelinux.cfg"
  MACADDR=""
  valid_hostname
  getMacAdress
  writePXE
  configure_dhcpd
  start_services
}
