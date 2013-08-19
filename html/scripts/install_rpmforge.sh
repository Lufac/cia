#!/bin/bash
[[ ! -d extras ]] && echo "creando directorio /root/extras" && mkdir extras
cd extras
wget http://packages.sw.be/rpmforge-release/rpmforge-release-0.5.2-2.el6.rf.x86_64.rpm
rpm --import http://apt.sw.be/RPM-GPG-KEY.dag.txt
rpm -K rpmforge-release-0.5.2-2.el6.rf.*.rpm
rpm -i rpmforge-release-0.5.2-2.el6.rf.*.rpm
yum -y install htop
