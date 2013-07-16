CUDA_PKG=cuda_5.0.35_linux_64_rhel6.x-1.run
wget http://192.168.1.250/extras/$CUDA_PKG
chmod u+x $CUDA_PKG
./$CUDA_PKG -silent -driver -toolkit -toolkitpath="/usr/local/cuda" -samples -samplespath="/usr/local/cuda" -verbose -override
if [ -f /opt/cuda5/bin/nvcc ]; then
	echo "ch3m: Instalation success..."
	rm -f ./$CUDA_PKG
else
	echo "ch3m: Instalation FAILED..."
fi
