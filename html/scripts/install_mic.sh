#!/bin/bash
#validar que las tarjetas esten disponibles
MIC_PKG=mpss_gold_update_3-2.1.6720-15-rhel-6.4.tar
wget http://192.168.1.250/extras/$MIC_PKG
tar xvf mpss_gold_update_3-2.1.6720-15-rhel-6.4.tar
cd mpss_gold_update_3
yum install --nogpgcheck --noplugins --disablerepo=* *.rpm
ssh-keygen -q -t rsa -f ~/.ssh/id_rsa -N ""
#backup de los scripts de redes
cp -rf /etc/sysconfig/network-scripts /root/network-scripts.old
micctrl --initdefaults
#set bridge
micctrl --addbridge=br0 --type=external --ip=192.168.1.251
#set ip's para las tarjetas
micctrl --network=static --bridge=br0 --ip=192.168.1.231
echo "DEVICE=eth0 
NM_CONTROLLED=no 
TYPE=Ethernet 
ONBOOT=yes 
BRIDGE=br0" > /etc/sysconfig/network-scripts/ifcfg-eth0
#listar los mic instalados
#parser /etc/sysconfig/mic/mic0.conf
#para cambiar el hostname de cada mic




#if [ -f /opt/cuda5/bin/nvcc ]; then
#	echo "ch3m: Instalation success..."
#	rm -f ./$CUDA_PKG
#else
#	echo "ch3m: Instalation FAILED..."
#fi
