#CIA MASTER Kickstart Configurator

firewall --disabled
selinux --disabled
skipx
firstboot --disable

lang en_US.UTF-8
keyboard us
rootpw ironman
authconfig --enableshadow --passalgo=sha512 
timezone --utc America/Mexico_City
bootloader --location=mbr --append="elevator=deadline nomodeset rdblacklist=nouveau nouveau.modeset=0 console=ttyS1,115200 console=tty0"
services --disabled openct,libvirtd,ksm,ksmtuned,ibacm,certmonger,bluetooth,cgconfig,crond,irqbalance,cups,iptables,ip6tables,postfix,abrtd,kdump,NetworkManager,cachefilesd,cups,fcoe,iscsi,iscsid,libvirt-guests,portreserve,stap-server,abrt-ccpp,abrt-oops,libvirt-guests,lldpad,pcscd,rpcgssd,opensmd,rdma 

services --enabled rsh,rlogin,ntpd,cpuspeed,ipmi  


