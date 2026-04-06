<?php
/**
 * Date: 2016/8/3
 * Time: 14:54
 */
define('FIRST_REBATE',false);//是否开启首充返利  5% TODO关闭时间 2017-01-19 23:59:59

define('REBATE_MONEY',0.02);//每日亏损返利百分比
define('MULTIPLE_LOSS',3);//领取亏损返利的投注额与亏损额的比值,基础是3倍
define('FIRST_REBATE_MONEY_B',3);//每日首充返利所需的流水倍数
define('FIRST_REBATE_MONEY',0.01);//每日首充返利百分比
define('COMMISSION_RATE',0.0008);//每日提成百分比
define('CASH_FEE_RATE',0.02);//提现费率百分比
define('FREE_CASH_FEE_RATE',1);//免费提现流水倍数


define('MIN_CASH',500);//最小提现额


//score_log
define('L_FIRSTR_REBATE',210);//首充返利
define('L_EXTENSION_REBATE',21);//推荐奖励
define('L_REBATE',20);//每日亏损返利
define('L_ROULETTE',70);//每日转盘抽奖
define('L_RANKREBATE',80);//每日排行奖励

//中央银行
define('CEN_PROP_SCORE',10);//道具奖励
define('CEN_ACTIVITY_SCORE',9);//活动奖励
define('CEN_ONLINE_PAY_SCORE',4);//活动奖励online_pay_score

//tj
define('TJ_JJ','jjpoints');//救济积分统计tj
define('TJ_REBATE','rebate');//亏损返利tj
define('TJ_BOXPOINTS','boxpoints');//轮盘/开宝箱奖励tj
define('TJ_RANKPOINTS','rankingpoints');//排行榜奖励tj

//game_static
define('STATIC_REBATE',142);//每日返利,首充返利收发红包
define('STATIC_PACK',141);//收发红包
define('STATIC_PROP',107);//收发红包
define('STATIC_ACTIVITY',105);//活动奖励 
define('STATIC_EXTENSION',104);//推荐奖励

//
define('MIN_RECHARGE',500);//最小充值额
define('MIN_QRRECHARGE',50);//二维码最小充值额
define('MIN_AGENT_RECHARGE',50000);
define('AGENT_MODEL',true);//代理模式

