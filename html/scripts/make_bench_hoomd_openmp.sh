#HOOMD tambien se puede obtener del gi instrucciones em:
#http://codeblue.umich.edu/hoomd-blue/doc/page_compile_guide_linux_generic.html
#git clone https://codeblue.umich.edu/git/hoomd-blue code

SERVER=$1
HOOMD_PKG="hoomd_src.tgz"
TEST_SCRIPT="RunNVT.py"
BASEDIR="$(pwd)/hoomd_openmp"
cd /root
mkdir $BASEDIR
cd $BASEDIR
wget http://$SERVER/extras/$HOOMD_PKG
tar xzvf $HOOMD_PKG
INSTALL_PATH="$BASEDIR/hoomd-install"
cd $BASEDIR/hoomd_src #suponemos que este directorio viene en el tar
rm -f CMakeCache.txt
cmake . -DENABLE_OPENMP=ON -DSINGLE_PRECISION=OFF -DCMAKE_INSTALL_PREFIX=$INSTALL_PATH
make install -j4
TEST_PATH="$BASEDIR/hoomd-test"
mkdir $TEST_PATH
cd $TEST_PATH
wget http://$SERVER/extras/$TEST_SCRIPT
echo "Running Benchmark.... "
$INSTALL_PATH/bin/hoomd $TEST_SCRIPT 1024 0.85 100 0.25 &> hoomd.bench.out
out_bench=$(cat $TEST_PATH/hoomd.bench.out | grep '** run complete **' )
if [ "$out_bench" != "** run complete **"  ]; then
  echo -e "\e[33m ch3m: Hoomd benchmark FAILED...\e[0m"
else
  echo -e "\e[91m ch3m: Hoomd benchmark success...\e[0m"
fi
