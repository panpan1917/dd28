#!/bin/bash

cd /alidata/www/kdy28/caiji

PROC_NAME="createRanking"
ProcNumber=`ps -ef |grep -w $PROC_NAME|grep -v grep|wc -l` 
if [ $ProcNumber -le 0 ];then 
	/alidata/server/php/bin/php createRanking.php 0 >/dev/null 2>&1 &
fi 











