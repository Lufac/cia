git clone https://github.com/vetter/shoc
mv shoc shoc_bench
cd shoc_bench
export PATH=$PATH:/opt/cuda5/bin
export LD_LIBRARY_PATH=/opt/cuda5/lib64:/opt/cuda5/lib
./configure --with-cuda
make
make install
cd bin/
./shocdriver -cuda
