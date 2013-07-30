#!/bin/bash
l=$(grep "^UID_MIN" /etc/login.defs)
l1=$(grep "^UID_MAX" /etc/login.defs)
users=$(awk -F':' -v "min=${l##UID_MIN}" -v "max=${l1##UID_MAX}" '{ if ( $3 >= min && $3 <= max ) print $0}' /etc/passwd)
for i in $users
do
  uid=$(echo $i | cut -d: -f3)
  user=$(echo $i | cut -d: -f1)
  gid=$(echo $i | cut -d: -f4)
  echo "Creating $user - $uid - $gid ...."
  micctrl --useradd=$user --uid=$uid --gid=$gid --home=/home/$user --sshkeys=/home/$user/.ssh
done
