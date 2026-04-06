#!/bin/bash

cd /alidata/www/kdy28/caiji

PROC_NAME="pressrollback"
ProcNumber=`ps -ef |grep -w $PROC_NAME|grep -v grep|wc -l` 
if [ $ProcNumber -le 0 ];then 
	/alidata/server/php/bin/php pressrollback.php >/dev/null 2>&1 &
fi 










