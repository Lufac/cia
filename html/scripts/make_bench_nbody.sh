#HOOMD tambien se puede obtener del gi instrucciones em:
#http://codeblue.umich.edu/hoomd-blue/doc/page_compile_guide_linux_generic.html
#git clone https://codeblue.umich.edu/git/hoomd-blue code

HOOMD_PKG="hoomd-openmp.tgz"
TEST_SCRIPT="RunNVT.py"
BASEDIR="$(pwd)/hoomd"
mkdir $BASEDIR
cd $BASEDIR
wget http://192.168.1.250/extras/$HOOMD_PKG
tar xzvf $HOOMD_PKG
INSTALL_PATH="$BASEDIR/hoomd-install"
cd $BASEDIR/hoomd_openmp_src #suponemos que este directorio viene en el tar
rm -f CMakeCache.txt
cmake . -DENABLE_OPENMP=ON -DSINGLE_PRECISION=OFF -DCMAKE_INSTALL_PREFIX=$INSTALL_PATH
make install -j4
TEST_PATH="$BASEDIR/hoomd-test"
mkdir $TEST_PATH
cd $TEST_PATH
wget http://192.168.1.250/extras/$TEST_SCRIPT
echo "Running Benchmark.... "
$INSTALL_PATH/bin/hoomd $TEST_SCRIPT 1024 0.85 100 0.25 &> hoomd.bench.out
if [ "$(cat $TEST_PATH/hoomd.bench.log | grep '** run complete **' )" != ""  ]; then
	echo "ch3m: Hoomd benchmark success..."
else
	echo "ch3m: Hoomd benchmark FAILED..."
fi
