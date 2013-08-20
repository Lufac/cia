#!/bin/bash
SERVER=$1
TAR_FILE="ch3m_files.tgz"
wget http://$SERVER/extras/$TAR_FILE
DIR_ORIGINAL=$(pwd)
cd /
tar xzvf $DIR_ORIGINAL/$TAR_FILE
cd $DIR_ORIGINAL
rm -f $TAR_FILE