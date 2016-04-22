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
$goodsInfo = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_by_id($ordersinfo['goods_id']);
if(!preg_match('/^http/', $goodsInfo['goods_pic']) ){
    $goods_pic = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$goodsInfo['goods_pic'];
}else{
    $goods_pic = $goodsInfo['goods_pic'];
}

$order_time = dgmdate($ordersinfo['order_time'], 'Y-m-d H:i:s',$tomSysOffset);

if($orderInfo['order_status'] == 1){
    if((TIMESTAMP - $orderInfo['order_time']) > 6900 ){
        $updateData = array();
        $updateData['order_status'] = 6;
        C::t('#tom_pintuan#tom_pintuan_order')->update($orderInfo['id'],$updateData);
        $orderInfo['order_status'] = 6;
        
        DB::query("UPDATE ".DB::table('tom_pintuan_goods')." SET goods_num=goods_num+{$orderInfo['goods_num']} WHERE id='{$orderInfo['goods_id']}'", 'UNBUFFERED');
        DB::query("UPDATE ".DB::table('tom_pintuan_goods')." SET sales_num=sales_num-{$orderInfo['goods_num']} WHERE id='{$orderInfo['goods_id']}'", 'UNBUFFERED');
                
    }
}

$stateStr = "";
if( $ordersinfo['order_status'] == 1 || $ordersinfo['order_status'] == 2 || $ordersinfo['order_status'] == 3){
    $stateStr = "state_1";
}
if( $ordersinfo['order_status'] == 4){
    $stateStr = "state_2";
}
if( $ordersinfo['order_status'] == 5){
    $stateStr = "state_3";
}

$ajaxPayUrl = "plugin.php?id=tom_pintuan:pay&act=pay&formhash=".FORMHASH;
$ajaxCancelPayUrl = "plugin.php?id=tom_pintuan:pay&act=cancelpay&formhash=".FORMHASH;
$ajaxQrshUrl = "plugin.php?id=tom_pintuan:pay&act=qrsh&formhash=".FORMHASH;
$backUrl = $_G['siteurl']."plugin.php?id=tom_pintuan&mod=orders&order_id=".$order_id;
$backUrl = urlencode($backUrl);

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_pintuan:ordersinfo");  

?>
