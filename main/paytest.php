<?php
require_once '../core/payment/payment5.php';
$payment = new payment();
$payment->setAccount(2);
$payResult = $payment->quickPayRequest("100000044" , 170 , 100345 , "userTest");


