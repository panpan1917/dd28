#!/bin/bash

cd /alidata/www/kdy28/caiji

PROC_NAME="Beijing"
ProcNumber=`ps -ef |grep -w $PROC_NAME|grep -v grep|wc -l` 
if [ $ProcNumber -le 0 ];then 
   /alidata/server/php/bin/php crawler.php source=Beijing >/dev/null 2>&1 &
fi 

PROC_NAME="Canada"
ProcNumber=`ps -ef |grep -w $PROC_NAME|grep -v grep|wc -l` 
if [ $ProcNumber -le 0 ];then 
   /alidata/server/php/bin/php crawler.php source=Canada >/dev/null 2>&1 &
fi 

PROC_NAME="Korea"
ProcNumber=`ps -ef |grep -w $PROC_NAME|grep -v grep|wc -l` 
if [ $ProcNumber -le 0 ];then 
   /alidata/server/php/bin/php crawler.php source=Korea >/dev/null 2>&1 &
fi 

PROC_NAME="Pk"
ProcNumber=`ps -ef |grep -w $PROC_NAME|grep -v grep|wc -l` 
if [ $ProcNumber -le 0 ];then 
   /alidata/server/php/bin/php crawler.php source=Pk >/dev/null 2>&1 &
fi 

PROC_NAME="LuckFarm"
ProcNumber=`ps -ef |grep -w $PROC_NAME|grep -v grep|wc -l` 
if [ $ProcNumber -le 0 ];then 
   /alidata/server/php/bin/php crawler.php source=LuckFarm >/dev/null 2>&1 &
fi


PROC_NAME="CQSSC"
ProcNumber=`ps -ef |grep -w $PROC_NAME|grep -v grep|wc -l` 
if [ $ProcNumber -le 0 ];then 
   /alidata/server/php/bin/php crawler.php source=CQSSC >/dev/null 2>&1 &
fi


PROC_NAME="Airship"
ProcNumber=`ps -ef |grep -w $PROC_NAME|grep -v grep|wc -l` 
if [ $ProcNumber -le 0 ];then 
   /alidata/server/php/bin/php crawler.php source=Airship >/dev/null 2>&1 &
fi


find ./ -mtime +2 -name "*_game*.log" -exec rm -rf {} \;
find ./ -mtime +2 -name "*_pressrollback_*.log" -exec rm -rf {} \;







