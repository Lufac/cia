<?php
function write_packages($opt){
  $tmpstr	 = "
install
%packages --ignoremissing
  ";
  if(!strcmp($opt->ks_type,"base") || !strcmp($opt->ks_type,"master") ){
    $tmpstr .= "
@additional-devel
@ backup-server
@ base
@cifs-file-server
@client-mgmt-tools
@compat-libraries
@ console-internet
@ core
@ debugging
@ development
@ desktop
@desktop-debugging
@desktop-platform
@desktop-platform-devel
@ emacs
@ hardware-monitoring
@ identity-management-server
@ infiniband
@ legacy-unix
@mail-server
@fonts
@general-desktop
@graphical-admin-tools
@graphics
@hardware-monitoring
@infiniband
@input-methods
@internet-browser
@java-platform
@large-systems
@legacy-unix
@legacy-x
@network-server
@network-file-system-client
@network-tools
@php
@performance
@perl-runtime
@remote-desktop-clients
@ruby-runtime
@system-management-snmp
@scientific
@server-platform
@server-platform-devel
@server-policy
@spanish-support
@system-management
@system-admin-tools
@system-management-messaging-server
@tex
@technical-writing
@web-server
@x11
@ network-server
@ network-file-system-client
@ network-tools
@ performance
@ perl-runtime
@ scalable-file-systems
@ scientific
@ server-platform-devel
@ server-policy
@ spanish-support
@ system-admin-tools
@ technical-writing

httpd-devel
pcre-devel
libcap-devel
libXinerama-devel
openmotif-devel
net-snmp-devel
libgudev1-devel
xz-devel
libtopology-devel
libibverbs-devel
libuuid-devel
libblkid-devel
papi-devel
libXmu-devel
unique-devel
xorg-x11-proto-devel
gmp-devel
perl-Test-Pod
startup-notification-devel
libudev-devel
cups-devel
unixODBC-devel
tcl-devel
numactl-devel
libgnomeui-devel
libbonobo-devel
perl-Test-Pod-Coverage
libtiff-devel
SDL-devel
libXau-devel
tcp_wrappers-devel
libgcrypt-devel
popt-devel
libusb-devel
hunspell-devel
iptables-devel
libdrm-devel
libXrandr-devel
libxslt-devel
tk-devel
libnl-devel
libXpm-devel
expat-devel
libglade2-devel
libaio-devel
gnutls-devel
fuse-devel
libXaw-devel
libhugetlbfs-devel
udftools
mtools
dumpet
gpm
pax
python-dmidecode
uuidd
yum-presto
oddjob
squashfs-tools
kernel-doc
tunctl
yum-plugin-downloadonly
sgpio
yum-plugin-changelog
genisoimage
dos2unix
unix2dos
ncurses-term
logwatch
zsh
wodim
mutt
tigervnc-server
oprofile-gui
xrestop
systemtap-grapher
gnome-common
gtk2-devel-docs
glade3
desktop-file-utils
gnome-devel-docs
systemtap-sdt-devel
mod_dav_svn
ElectricFence
ant
libstdc++-docs
expect
perltidy
cmake
imake
babel
rpmdevtools
compat-gcc-34
systemtap-server
compat-gcc-34-g77
jpackage-utils
mercurial
gcc-objc
rpmlint
gcc-objc++
compat-gcc-34-c++
python-docs
nasm
certmonger
samba
emacs-nox
ctags-etags
emacs-gnuplot
emacs-auctex
bitmap-lucida-typewriter-fonts
gconf-editor
alacarte
gedit-plugins
vim-X11
system-config-lvm
audit-viewer
firstaidkit-gui
system-config-kickstart
system-config-keyboard
netpbm-progs
xfig
ImageMagick
inkscape
dcraw
lm_sensors
qperf
perftest
libibcommon
compat-dapl
infiniband-diags
srptools
opensm
rsh
rsh-server
tcp_wrappers
finger
tftp
xorg-x11-twm
openmotif
xterm
xorg-x11-xdm
xorg-x11-fonts-75dpi
libXmu
libXp
openmotif22
syslinux
dnsmasq
dhcp
tftp-server
cachefilesd
nmap
stunnel
iptraf
wireshark
planner
taskjuggler
papi
perl-DBD-SQLite
perl-Date-Manip
spice-xpi
tsclient
rdesktop
vinagre
tigervnc
spice-client
net-snmp-python
net-snmp-perl
numpy
freeipmi-ipmidetectd
ipmitool
freeipmi
freeipmi-bmc-watchdog
watchdog
openhpi-subagent
OpenIPMI
openhpi
symlinks
pexpect
dtach
mc
xdelta
screen
tree
mgetty
hardlink
expect
conman
crypto-utils
scrub
rdist
vlock
xmltoman
texinfo
docbook-utils-pdf
xmlto-tex
certmonger
perl-CGI
-mysql-devel
-abrt-desktop
-spamassassin
-libcxgb3
-hwloc
-rrdtool
apr-devel
asciidoc
automake
binutils-devel
bzip2-devel
cairo-devel
cmake
cmake-gui
compat-dapl-devel
device-mapper-multipath
dhcp
elfutils-devel
elfutils-libelf-devel
expat-devel
expect
freeglut-devel
gcc
gdbm-devel
glib2-devel
glibc.i686
libstdc++.i686
hmaccalc
infiniband-diags
iptraf
libacl-devel
libattr-devel
libevent-devel
libibcm-devel
libibcommon-devel
libibmad-devel
libibumad-devel
libibverbs-devel
libmlx4-devel
librdmacm-devel
libselinux-devel
libsysfs-devel
libXcomposite-devel
libXdamage-devel
libXi-devel
libxml2-devel
libXp-devel
libXScrnSaver-devel
libXvMC-devel
lua-devel
nmap
make
mc
mesa-libGLU
mesa-libGLw-devel
mesa-libOSMesa-devel
newt-devel
numactl-devel
openmotif-devel
opensm
pango-devel
patchutils
pciutils-devel
pcre-devel
perl-Compress-Zlib
perl-ExtUtils-Embed
php-gd
python-devel
readline-devel
redhat-rpm-config
rpm-build
rsh
rsh-server
ruby-devel
slang-devel
sysstat
tcl-devel
texlive-latex
tk-devel
tftp-server
tigervnc-server
tigervnc
xmlto
xorg-x11-fonts-Type1
xulrunner-devel
zlib-devel

-libvirt-client
-libvirt-java
-virt-what
-krb5-workstation
-sysreport
-libsdp
-oprofile
-sabayon
-openmpi
-openmpi-libs
-mpi-selector
-seekwatcher
-python-matplotlib
-numpy
-atlas
-PackageKit
  ";
  }else if(!strcmp($opt->ks_type,"minima")){
    $tmpstr .= "
@base
@Development Tools
cmake
zlib-devel
boost-devel
python-devel
    ";
  }
	return $tmpstr;
}
?>
