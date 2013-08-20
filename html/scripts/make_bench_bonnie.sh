#!/bin/bash
#http://www.googlux.com/bonnie.html
wget http://www.coker.com.au/bonnie++/bonnie++-1.03e.tgz
tar xzvf bonnie++-1.03e.tgz
cd bonnie++-1.03e
./configure
make
cd ..
mkdir /tmp/bonnie-local
MEM=$(cat /proc/meminfo | grep MemTotal | awk '{print $2}')
DISK_SIZE="$(python -c "print int(${MEM}/1024/1024*2)+10")g"
echo -e "\e[33m +++++++++++++++++++++++++++++++++++++\e[0m"
echo -e "\e[33m Memory Size Decteted $MEM...\e[0m"
echo -e "\e[33m Disk Size bench $DISK_SIZE...\e[0m"
echo -e "\e[33m +++++++++++++++++++++++++++++++++++++\e[0m"
./bonnie++-1.03e/bonnie++ -d /tmp/bonnie-local -s $DISK_SIZE -n 0 -m $HOSTNAME -f -b -u root
echo "Time seg: $SECONDS"
