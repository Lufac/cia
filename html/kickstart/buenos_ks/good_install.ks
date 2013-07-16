text
install
url --url http://192.168.1.250/centos6
lang en_US.UTF-8
keyboard us
network --bootproto=static --device=eth0 --ip=192.168.1.130 --netmask=255.255.255.0 --gateway=192.168.1.1 --nameserver=8.8.8.8
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
reboot
#

services --disabled openct,libvirtd,ksm,ksmtuned,ibacm,certmonger,bluetooth,cgconfig,crond,irqbalance,cups,iptables,ip6tables,postfix,abrtd,kdump,NetworkManager,cachefilesd,cups,fcoe,iscsi,iscsid,libvirt-guests,portreserve,stap-server,abrt-ccpp,abrt-oops,libvirt-guests,lldpad,pcscd,rpcgssd,opensmd,rdma
services --enabled rsh,rlogin,ntpd,cpuspeed,ipmi

install
%packages --ignoremissing
@additional-devel
@backup-server
@base
@cifs-file-server
@client-mgmt-tools
@compat-libraries
@console-internet
@core
@debugging
@desktop
@desktop-debugging
@desktop-platform
@desktop-platform-devel
@development
@emacs
@fonts
@general-desktop
@graphical-admin-tools
@graphics
@hardware-monitoring
@identity-management-server
@infiniband
@input-methods
@internet-browser
@java-platform
@large-systems
@legacy-unix
@legacy-x
@mail-server
@network-file-system-client
@network-server
@network-tools
@performance
@perl-runtime
@php
@remote-desktop-clients
@ruby-runtime
@scalable-file-systems
@scientific
@server-platform
@server-platform-devel
@server-policy
@spanish-support
@system-admin-tools
@system-management
@system-management-messaging-server
@system-management-snmp
@technical-writing
@Virtualization
@Virtualization Client
@Virtualization Platform
@Virtualization Tools
@web-server
@x11

alacarte
ant
apr-devel
asciidoc
audit-viewer
automake
babel
binutils-devel
bitmap-lucida-typewriter-fonts
bzip2-devel
cachefilesd
cairo-devel
certmonger
cmake
cmake-gui
compat-dapl
compat-dapl-devel
compat-gcc-34
compat-gcc-34-c++
compat-gcc-34-g77
compat-gcc-34.x86_64
compat-libstdc++-33.i686
compat-libstdc++-33.x86_64
conman
crypto-utils
ctags-etags
cups-devel
dcraw
desktop-file-utils
device-mapper-multipath
dhcp
dnsmasq
docbook-utils-pdf
dos2unix
dtach
dumpet
ElectricFence
elfutils-devel
elfutils-libelf-devel
emacs-auctex
emacs-gnuplot
emacs-nox
expat-devel
expect
finger
firstaidkit-gui
freeglut-devel
freeipmi
freeipmi-bmc-watchdog
freeipmi-ipmidetectd
fuse-devel
gcc
gcc-c++.x86_64
gcc-objc
gcc-objc++
gconf-editor
gdbm-devel
gedit-plugins
genisoimage
glade3
glib2-devel
glibc.i686
gmp-devel
gnome-common
gnome-devel-docs
gnutls-devel
gpm
gtk2-devel-docs
gtk2.i686
hardlink
hmaccalc
httpd-devel
hunspell-devel
ImageMagick
imake
infiniband-diags
inkscape
ipmitool
iptables-devel
iptraf
jpackage-utils
kernel-doc
libacl-devel
libaio-devel
libattr-devel
libblkid-devel
libbonobo-devel
libcap-devel
libdrm-devel
libevent-devel
libgcrypt-devel
libglade2-devel
libgnomeui-devel
libgudev1-devel
libhugetlbfs-devel
libibcm-devel
libibcommon
libibcommon-devel
libibmad-devel
libibumad-devel
libibverbs-devel
libibverbs-devel
libpciaccess-devel
libmlx4-devel
libnl-devel
librdmacm-devel
libselinux-devel
libSM.i686
libstdc++-devel.i686
libstdc++-devel.x86_64
libstdc++-docs
libstdc++.i686
libstdc++.x86_64
libsysfs-devel
libtiff-devel
libtopology-devel
libudev-devel
libusb-devel
libuuid-devel
libX11.i686
libXau-devel
libXau.i686
libXaw-devel
libxcb.i686
libXcomposite-devel
libXdamage-devel
libXext.i686
libXi-devel
libXi.i686
libXinerama-devel
libxml2-devel
libXmu
libXmu-devel
libXp
libXp-devel
libXpm-devel
libXp.x86_64
libXrandr-devel
libXScrnSaver-devel
libxslt-devel
libXt.i686
libXtst.i686
libXvMC-devel
libXxf86vm
libXxf86vm.i686
lm_sensors
logwatch
lua-devel
make
mc
mercurial
mesa-libGLU
mesa-libGLw-devel
mesa-libOSMesa-devel
mgetty
mod_dav_svn
mtools
mutt
nasm
ncurses-term
netpbm-progs
net-snmp-devel
net-snmp-perl
net-snmp-python
newt-devel
nmap
numactl-devel
numpy
oddjob
openhpi
openhpi-subagent
OpenIPMI
openmotif
openmotif22
openmotif-devel
openmotif.x86_64
opensm
oprofile-gui
pango-devel
papi
papi-devel
patchutils
pax
pciutils-devel
pcre-devel
pcre-devel
perftest
perl-CGI
perl-Compress-Zlib
perl-Date-Manip
perl-DBD-SQLite
perl-ExtUtils-Embed
perl-Test-Pod
perl-Test-Pod-Coverage
perltidy
pexpect
php-gd
planner
popt-devel
python-devel
python-dmidecode
python-docs
qperf
rdesktop
rdist
readline-devel
redhat-rpm-config
rpm-build
rpmdevtools
rpmlint
rsh
rsh-server
ruby-devel
samba
screen
scrub
SDL-devel
sgpio
slang-devel
spice-client
spice-xpi
squashfs-tools
srptools
startup-notification-devel
stunnel
symlinks
syslinux
sysstat
system-config-kickstart
system-config-lvm
systemtap-sdt-devel
systemtap-server
taskjuggler
tcl-devel
tcl-devel
tcp_wrappers
tcp_wrappers-devel
texinfo
texlive-latex
tftp
tftp-server
tigervnc
tigervnc-server
tk-devel
tk-devel
tree
tsclient
tunctl
udftools
unique-devel
unix2dos
unixODBC-devel
uuidd
vim-X11
vinagre
vlock
watchdog
wireshark
wodim
xdelta
xfig
xmlto
xmltoman
xmlto-tex
xorg-x11-fonts-75dpi
xorg-x11-fonts-cyrillic.noarch
xorg-x11-fonts-ISO8859-1-75dpi.noarch
xorg-x11-fonts-Type1
xorg-x11-proto-devel
xorg-x11-twm
xorg-x11-xdm
xrestop
xterm
xterm.x86_64
xulrunner-devel
xz-devel
yum-plugin-changelog
yum-plugin-downloadonly
yum-presto
zlib-devel
zsh
-abrt-desktop
-anaconda
-atlas
-hwloc
-krb5-workstation
-libcxgb3
-libsdp
-libvirt-client
-libvirt-java
-mpi-selector
-mysql-devel
-NetworkManager
-NetworkManager-gnome
-numpy
-openmpi
-openmpi-libs
-oprofile
-PackageKit
-python-matplotlib
-rrdtool
-sabayon
-seekwatcher
-spamassassin
-sysreport
-system-config-kickstart
-virt-what


%post
#chkconfig --level 3 ip6tables off
#chkconfig --level 3 kudzu off
#chkconfig --level 3 netfs off
#chkconfig --level 3 yum-updatesd off
#
/etc/init.d/sshd start
wget http://192.168.1.250/scripts/install_cuda_sdk.sh
bash install_cuda_sdk.sh
wget http://192.168.1.250/scrpits/make_bench_shoc.sh
bash make_bench_shoc.sh
#cat /boot/grub/grub.conf | sed 's/console=xvc0//g' &> /tmp/grub.tmp
#mv -f /tmp/grub.tmp /boot/grub/grub.conf
