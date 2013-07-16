text
install
url --url http://192.168.1.250/centos6
lang en_US.UTF-8
keyboard us
network --bootproto=static --device=eth0 --ip=192.168.1.210 --netmask=255.255.255.0 --gateway=192.168.1.1 --nameserver=8.8.8.8 --hostname master
#rootpw --iscrypted $1$/KEoeArl$f5TokNUdzGIqlvRCLFWW9/
rootpw ironman 

skipx
firewall --disabled
selinux --disabled
authconfig --enableshadow --enablemd5
timezone --utc America/Mexico_City
bootloader --location=mbr --append="elevator=deadline nomodeset rdblacklist=nouveau nouveau.modeset=0 console=ttyS1,115200 console=tty0"


#Pariticionamiento
#zerombr yes
clearpart --all
part /boot --asprimary --fstype="ext3" --size=100 --bytes-per-inode=4096
part swap --asprimary --fstype="swap" --recommended --bytes-per-inode=4096
part / --asprimary --fstype="ext3" --grow --size=10000 --bytes-per-inode=4096
#particionado el resto
#part / --asprimary --fstype="ext3" --grow --size=1 --bytes-per-inode=4096
#reboot
#

services --disabled openct,libvirtd,ksm,ksmtuned,ibacm,certmonger,bluetooth,cgconfig,crond,irqbalance,cups,iptables,ip6tables,postfix,abrtd,kdump,NetworkManager,cachefilesd,cups,fcoe,iscsi,iscsid,libvirt-guests,portreserve,stap-server,abrt-ccpp,abrt-oops,libvirt-guests,lldpad,pcscd,rpcgssd,opensmd,rdma
services --enabled rsh,rlogin,ntpd,cpuspeed,ipmi

install
%packages --ignoremissing
@base
@Development Tools
cmake
zlib-devel
boost-devel
python-devel

%post
#chkconfig --level 3 ip6tables off
#chkconfig --level 3 kudzu off
#chkconfig --level 3 netfs off
#chkconfig --level 3 yum-updatesd off
#
/etc/init.d/sshd start
cd /root
wget http://192.168.1.250/scripts/install_cuda_sdk.sh &> install.cuda.log
bash install_cuda_sdk.sh &>> install.cuda.log
wget http://192.168.1.250/scripts/install_rpmforge.sh &> install_rpmforge.log
bash install_rpmforge.sh &>> install_rpmforge.log
wget http://192.168.1.250/scripts/make_bench_shoc.sh &> shoc.bench.log
bash make_bench_shoc.sh &>> shoc.bench.log
wget http://192.168.1.250/scripts/make_bench_hoomd.sh &> hoomd.bench.log
bash make_bench_hoomd.sh &>> hoomd.bench.log
#cat /boot/grub/grub.conf | sed 's/console=xvc0//g' &> /tmp/grub.tmp
#mv -f /tmp/grub.tmp /boot/grub/grub.conf
