#!/bin/bash
SERVER=$1
[[ ! -d extras ]] && echo "creando directorio /root/extras" && mkdir extras
cd extras
mkdir cia_packages
cd cia_packages
wget -r -l1 --no-parent -A ".rpm" -nH --cut-dirs=5  http://$SERVER/extras/cia_package/x86_64/MASTER/
wget -r -l1 --no-parent -A ".rpm" -nH --cut-dirs=5  http://$SERVER/extras/cia_package/noarch/
rpm -Uvh *.rpm