<?php
function write_partioning(& $ksfile, $storage){
  if(!strcmp($storage,"softraid")){
    $ksfile .= "
clearpart --all
part raid.01 --size=100000  --ondisk=sda
part raid.02 --size=18000 --ondisk=sda
part raid.03 --size=1 --grow --ondisk=sda  

part raid.11 --size=100000  --ondisk=sdb
part raid.12 --size=18000 --ondisk=sdb
part raid.13 --size=1 --grow --ondisk=sdb

raid / --level=RAID1 --device=md0 --fstype=ext4 raid.01 raid.11
raid swap  --level=RAID1 --device=md1 --fstype=ext4 raid.02 raid.12
raid /home --level=RAID1 --device=md2 --fstype=ext4 raid.03 raid.13
    ";
  }else{
    $ksfile .= "
clearpart --all
part /boot --asprimary --fstype=ext4 --size=100 --bytes-per-inode=4096
part swap --asprimary --fstype=swap --recommended --bytes-per-inode=4096
part / --asprimary --fstype=ext4 --size=30000 --bytes-per-inode=4096
    ";
  };
}
?>
