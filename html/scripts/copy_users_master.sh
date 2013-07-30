#!/bin/bash
ip="192.168.1.251"
l=$(grep "^UID_MIN" /etc/login.defs)
l1=$(grep "^UID_MAX" /etc/login.defs)
users=$(awk -F':' -v "min=${l##UID_MIN}" -v "max=${l1##UID_MAX}" '{ if ( $3 >= min && $3 <= max ) print $0}' /etc/passwd)
for passwd_line  in $users
do
  uid=$(echo $passwd_line | cut -d: -f3)
  user=$(echo $passwd_line | cut -d: -f1)
  gid=$(echo $passwd_line | cut -d: -f4)
  shadow_line=$(cat /etc/shadow | grep $user)
  pass=$(echo $shadow_line | cut -d: -f2)
  group_line=$(cat /etc/group | grep $gid)   
  if [ $pass != "!!" ];then
    echo -e "-------------------\n++++++++++++++++++\n\t$user\n++++++++++++++++++"
    echo "Copiando passwd_line: $passwd_line"
    command="echo \"$passwd_line\" >> /etc/passwd"
    ssh -x $ip "$command"
    echo "Copiando shadow_line: $shadow_line"
    command="echo \"$shadow_line\" >> /etc/shadow"
    ssh -x $ip "$command"
    echo "Copiando group_line: $group_line"
    command="echo \"$group_line\" >> /etc/group"
    ssh -x $ip "$command"
  fi
done
