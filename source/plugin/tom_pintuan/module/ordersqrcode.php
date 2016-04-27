<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$order_id = isset($_GET['order_id'])? intval($_GET['order_id']):0;

$ordersinfo = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($order_id);

$qrcodeImg = $_G['siteurl']."plugin.php?id=tom_qrcode&data=".urlencode($_G['siteurl']."plugin.php?id=tom_pintuan&mod=orderssure&order_no={$ordersinfo['order_no']}");

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_pintuan:ordersqrcode");  

?>
