#!/bin/bash
server=$1
fecha=$(date +%d-%b-%y_%H-%M)
tempfile="/tmp/tmpfile.$$"
echo "Creando $tempfile"
find /root/.install_post -name "*.log" > $tempfile
echo "/root/anaconda-ks.cfg" >> $tempfile
echo "/root/install.log" >> $tempfile
echo "/proc/cpuinfo" >> $tempfile
echo "/proc/meminfo" >> $tempfile
update-pciids &> /dev/null
lspci > /tmp/pci.devices
echo "/tmp/pci.devices" >> $tempfile

tar_name="/tmp/logs_${HOSTNAME}_${fecha}.tgz"
tar czvf $tar_name -T $tempfile

#configure SSH
cd ~/.ssh
wget http://$server/extras/cialogs_key
mv cialogs_key id_rsa
chmod 600 id_rsa
rm -f cialogs_key.des
wget http://$server/extras/cialogs_known_hosts
mv cialogs_known_hosts known_hosts

scp -P 8080 $tar_name cialogs@$server: