#http://www.googlux.com/bonnie.html
wget http://www.coker.com.au/bonnie++/bonnie++-1.03e.tgz
./configure
make
./bonnie++-1.03e/bonnie++ -d /tmp/bonnie-local -s 128g -n 0 -m kepler -f -b -u root
