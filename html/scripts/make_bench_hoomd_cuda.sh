#HOOMD tambien se puede obtener del gi instrucciones em:
#http://codeblue.umich.edu/hoomd-blue/doc/page_compile_guide_linux_generic.html
#git clone https://codeblue.umich.edu/git/hoomd-blue code

SERVER=$1
HOOMD_PKG="hoomd_src.tgz"
TEST_SCRIPT="RunNVT.py"
BASEDIR="$(pwd)/hoomd_cuda"
cd /root
mkdir $BASEDIR
cd $BASEDIR
wget http://$SERVER/extras/$HOOMD_PKG
tar xzvf $HOOMD_PKG
INSTALL_PATH="$BASEDIR/hoomd-install"
cd $BASEDIR/hoomd_src #suponemos que este directorio viene en el tar
rm -f CMakeCache.txt
cmake . -DENABLE_CUDA=ON -DCMAKE_INSTALL_PREFIX=$INSTALL_PATH
make install -j4
TEST_PATH="$BASEDIR/hoomd-test"
mkdir $TEST_PATH
cd $TEST_PATH
wget http://$SERVER/extras/$TEST_SCRIPT
echo "Running Benchmark.... "
export LD_LIBRARY_PATH=$LD_LIBRARY_PATH:/usr/local/cuda/lib64
$INSTALL_PATH/bin/hoomd $TEST_SCRIPT 4096 0.85 100 0.25 &> hoomd.bench.out
out_bench=$(cat $TEST_PATH/hoomd.bench.out | grep '** run complete **' )
if [ "$out_bench" != "** run complete **"  ]; then
  echo -e "\e[33m ch3m: Hoomd benchmark FAILED...\e[0m"
else
  echo -e "\e[91m ch3m: Hoomd benchmark success...\e[0m"
fi
