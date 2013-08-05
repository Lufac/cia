SERVER=$1
CUDA_PKG=cuda_5.0.35_linux_64_rhel6.x-1.run
wget http://$SERVER/extras/$CUDA_PKG
chmod u+x $CUDA_PKG
./$CUDA_PKG -verbose -driver -silent -toolkit -samples -override
if [ -f /usr/local/cuda/bin/nvcc ]; then
	echo "ch3m: Instalation success..."
	rm -f ./$CUDA_PKG
else
	echo "ch3m: Instalation FAILED..."
fi
