# Descargado de http://www.cs.virginia.edu/stream/FTP/Code/
#!/bin/bash
SERVER=$1
stream="stream.c"
wget http://$SERVER/extras/$stream
gcc -openmp -O2 stream.c -o stream
export OMP_NUM_THREADS=1
./stream
