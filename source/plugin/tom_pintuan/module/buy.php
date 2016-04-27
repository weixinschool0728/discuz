<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$goods_id   = isset($_GET['goods_id'])? intval($_GET['goods_id']):0;
$address_id = isset($_GET['address_id'])? intval($_GET['address_id']):0;
$tstatus    = isset($_GET['tstatus'])? intval($_GET['tstatus']):0;
$tlevel    = isset($_GET['tlevel'])? intval($_GET['tlevel']):1;
$tuan_id    = isset($_GET['tuan_id'])? intval($_GET['tuan_id']):0;
$take_type    = isset($_GET['take_type'])? intval($_GET['take_type']):1;

$goodsInfo = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_by_id($goods_id);

if(!preg_match('/^http/', $goodsInfo['goods_pic']) ){
    $goods_pic = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$goodsInfo['goods_pic'];
}else{
    $goods_pic = $goodsInfo['goods_pic'];
}

$addressListCount = C::t('#tom_pintuan#tom_pintuan_address')->fetch_all_count(" AND user_id={$__UserInfo['id']} ");

if($addressListCount == 0){
    dheader('location:'.$_G['siteurl']."plugin.php?id=tom_pintuan&mod=address&act=add&tuan_id={$tuan_id}&tstatus={$tstatus}&tlevel={$tlevel}&bstatus=1&goods_id={$goods_id}");exit;
}

$addressInfo = array();
$addressInfoTmp = C::t('#tom_pintuan#tom_pintuan_address')->fetch_by_id($address_id);
if($addressInfoTmp){
    $addressInfo = $addressInfoTmp;
}else{
    $defaultAddressList = C::t('#tom_pintuan#tom_pintuan_address')->fetch_all_list(" AND user_id={$__UserInfo['id']} AND default_id=1 ","ORDER BY id DESC",0,1);
    if($defaultAddressList){
        $addressInfo = $defaultAddressList['0'];
    }else{
        dheader('location:'.$_G['siteurl']."plugin.php?id=tom_pintuan&mod=address&act=add&tuan_id={$tuan_id}&tstatus={$tstatus}&tlevel={$tlevel}&bstatus=1&goods_id={$goods_id}");exit;
    }
}

$showChooseBtn = 0;
if($goodsInfo['take_type'] == 1){
    $take_type = 1;
}else if($goodsInfo['take_type'] == 2){
    $take_type = 2;
}else if($goodsInfo['take_type'] == 4){
    $take_type = 4;
}else{
    $showChooseBtn = 1;
}

if($goodsInfo['express_price'] > 0){
    $pintuanConfig['express_price'] = $goodsInfo['express_price'];
}

//意合微信-2015-11-22虚拟商品免邮费
if($take_type == 2 || $take_type == 4){
    $pintuanConfig['express_price'] = 0;
}

//意合工作室更新20151022限时购买
$start_time = dgmdate($goodsInfo['start_time'],"Y-m-d",$tomSysOffset);
$end_time = dgmdate($goodsInfo['end_time'],"Y-m-d",$tomSysOffset);
$daojishiTimes = $goodsInfo['end_time']-TIMESTAMP;
$showBtnBox = 0;
if($goodsInfo['start_time'] != 0){
	$showBtnBox = 1;
	if(TIMESTAMP < $goodsInfo['start_time']){
		$showBtnBox = 2;
	}
	
	if(TIMESTAMP > $goodsInfo['end_time']){
		$showBtnBox = 3;
	}
}
//end意合工作室更新20151022限时购买

$express_price = $pintuanConfig['express_price']/100;

$changeAddressUrl = "plugin.php?id=tom_pintuan&mod=address&tuan_id={$tuan_id}&tstatus={$tstatus}&tlevel={$tlevel}&bstatus=1&goods_id={$goods_id}&address_id={$addressInfo['id']}";

$ajaxPayUrl = "plugin.php?id=tom_pintuan:pay";

$ordersUrl = "plugin.php?id=tom_pintuan&mod=orders";
$tuanUrl = "plugin.php?id=tom_pintuan&mod=tuan&tlevel={$tlevel}&tuan_id=";

$tuan_price = $goodsInfo['tuan_price'];
if($tstatus == 1 || $tstatus == 2){
    if($tlevel == 1){
        $base_price = $goodsInfo['tuan_price']*100;
        $tuan_price = $goodsInfo['tuan_price'];
    }else if($tlevel == 2){
        $base_price = $goodsInfo['tuan_price_2']*100;
        $tuan_price = $goodsInfo['tuan_price_2'];
    }else if($tlevel == 3){
        $base_price = $goodsInfo['tuan_price_3']*100;
        $tuan_price = $goodsInfo['tuan_price_3'];
    }else{
        $base_price = $goodsInfo['tuan_price']*100;
    }
    
}else{
    $base_price = $goodsInfo['one_price']*100;
}

$pay_price_arr = array();
for($i=1;$i<=10;$i++){
    $pay_price_arr[$i] = ($pintuanConfig['express_price']+$base_price*$i)/100;
}

//团长价格
$show_tuanz_price = 0;
if($goodsInfo['tuanz_price'] > 0 && $tstatus == 1 || $tstatus == 2 && !$tuan_id){
	$show_tuanz_price = 1;
	$tuan_price = $goodsInfo['tuanz_price'];
	$pay_price_arr = array();
	for($i=1;$i<=10;$i++){
		$pay_price_arr[$i] = ($pintuanConfig['express_price']+$base_price*($i-1)+$goodsInfo['tuanz_price']*100)/100;
	}
}


$chooseTakeType1Url = "plugin.php?id=tom_pintuan&mod=buy&showwxpaytitle=1&take_type=1&tstatus={$tstatus}&tlevel={$tlevel}&tuan_id={$tuan_id}&address_id=$address_id&goods_id=".$goods_id;
$chooseTakeType2Url = "plugin.php?id=tom_pintuan&mod=buy&showwxpaytitle=1&take_type=2&tstatus={$tstatus}&tlevel={$tlevel}&tuan_id={$tuan_id}&address_id=$address_id&goods_id=".$goods_id;

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_pintuan:buy");  

?>
