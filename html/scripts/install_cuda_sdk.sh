SERVER=$1
install_type=$2
if [[ "$install_type" == "local" ]]; then
	echo -e "\e[33m +++++++++++++++++++++++++++++++++++++\e[0m"
	echo -e "\e[33m Local sever installation...\e[0m"
	echo -e "\e[33m +++++++++++++++++++++++++++++++++++++\e[0m"
	CUDA_PKG=cuda_5.0.35_linux_64_rhel6.x-1.run
	wget http://$SERVER/extras/$CUDA_PKG
	chmod u+x $CUDA_PKG
	./$CUDA_PKG -verbose -driver -silent -toolkit -samples -override
	#borrado de paquetes
	rm -f ./$CUDA_PKG
fi

if [[ "$install_type" == "yum" ]]; then
	echo -e "\e[33m +++++++++++++++++++++++++++++++++++++\e[0m"
	echo -e "\e[33m Net installation...\e[0m"
	echo -e "\e[33m +++++++++++++++++++++++++++++++++++++\e[0m"
	yum -y install dkms.noarch
	CUDA_RPM=cuda-repo-rhel6-5.5-0.x86_64.rpm
	wget http://developer.download.nvidia.com/compute/cuda/repos/rhel6/x86_64/$CUDA_RPM
	rpm -Uvh $CUDA_RPM
	yum clean expire-cache
	yum -y install cuda
	#Borrado de paquetes
	rm -f ./$CUDA_RPM
fi

echo -e "\e[36m +++++++++++++++++++++++++++++++++++++\e[0m"
if [ ! -f /usr/local/cuda/bin/nvcc ]; then
	echo -e "\e[91m ch3m: Hoomd benchmark FAILED...\e[0m"
else
	echo -e "\e[33m ch3m: Hoomd benchmark success...\e[0m"
fi
echo -e "\e[36m +++++++++++++++++++++++++++++++++++++\e[0m"