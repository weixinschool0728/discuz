<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define('TPL_DEFAULT', true);
$formhash = FORMHASH;
$pintuanConfig = $_G['cache']['plugin']['tom_pintuan'];

$order_no  = isset($_GET['order_no'])? addslashes($_GET['order_no']):'';

$orderInfo = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_order_no($order_no);

$goodsInfo = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_by_id($orderInfo['goods_id']);

if($goodsInfo['express_price'] > 0){
    $pintuanConfig['express_price'] = $goodsInfo['express_price'];
}

$order_time = dgmdate($orderInfo['order_time'], 'Y-m-d H:i:s',$tomSysOffset);

$goods_all_price = $orderInfo['goods_num']*$orderInfo['goods_price'];
$express_price = $pintuanConfig['express_price']/100;

if($orderInfo['take_type'] == 2){
    $express_price = 0;
}

$pay_all_price = $goods_all_price + $express_price;

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_pintuan:print");  

?>
