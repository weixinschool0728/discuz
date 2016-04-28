<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

//判断用户是否关注微信公众号（start）
$subscribeFlag = 0;
$access_token = $weixinClass->get_access_token();
if(!empty($__UserInfo['openid']) && !empty($access_token)){
    $get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$__UserInfo['openid']}&lang=zh_CN";
    $return = get_html($get_user_info_url);
    if(!empty($return)){
        $content = json_decode($return,true);
        if(is_array($content) && !empty($content) && isset($content['subscribe'])){
            if($content['subscribe'] == 1){
                $subscribeFlag = 1;
            }else{
                $subscribeFlag = 2;
            }
        }
    }
}
//判断用户是否关注微信公众号（end）
 file_put_contents("./upload/userinfo.txt",  print_r($weixinClass->get_user_info($__UserInfo['openid']),true)."---\r\n-----",FILE_APPEND);
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
}else{
    $showChooseBtn = 1;
}

if($goodsInfo['express_price'] > 0){
    $pintuanConfig['express_price'] = $goodsInfo['express_price'];
}

if($goodsInfo['express_id'] > 0 && !empty($addressInfo)){
    $expressInfo = C::t('#tom_pintuan#tom_pintuan_express')->fetch_by_id($goodsInfo['express_id']);
    if($expressInfo){
        $pintuanConfig['express_price'] = $expressInfo['default_price'];
        
        $express_itemList1 = C::t('#tom_pintuan#tom_pintuan_express_item')->fetch_all_list(" AND express_id={$goodsInfo['express_id']} AND province_id={$addressInfo['province_id']} AND city_id={$addressInfo['city_id']} "," ORDER BY id DESC ",0,1);
        if(!empty($express_itemList1) && isset($express_itemList1['0'])){
            $pintuanConfig['express_price'] = $express_itemList1['0']['express_price'];
        }else{
            $express_itemList2 = C::t('#tom_pintuan#tom_pintuan_express_item')->fetch_all_list(" AND express_id={$goodsInfo['express_id']} AND province_id={$addressInfo['province_id']} AND city_id=0 "," ORDER BY id DESC ",0,1);
            if(!empty($express_itemList2) && isset($express_itemList2['0'])){
                $pintuanConfig['express_price'] = $express_itemList2['0']['express_price'];
            }
        }
    }
}

if($take_type == 2){
    $pintuanConfig['express_price'] = 0;
}

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

$show_tuanz_price = 0;
if($tstatus == 1){
    if($goodsInfo['tuanz_price'] > 0){
        $show_tuanz_price = 1;
        $tuan_price = $goodsInfo['tuanz_price'];
        $base_price = $goodsInfo['tuanz_price']*100;
        if($goodsInfo['tuanz_price_num'] > 0){
                $goodsInfo['xiangou_num'] = $goodsInfo['tuanz_price_num'];
        }
    }
}

$goods_xiangou_num = 0;
if($goodsInfo['xiangou_num'] > 0){
    $goods_xiangou_num = $goodsInfo['xiangou_num'];
}

$pay_price_arr = array();
for($i=1;$i<=100;$i++){
    $pay_price_arr[$i] = ($pintuanConfig['express_price']+$base_price*$i)/100;
}



$chooseTakeType1Url = "plugin.php?id=tom_pintuan&mod=buy&showwxpaytitle=1&take_type=1&tstatus={$tstatus}&tlevel={$tlevel}&tuan_id={$tuan_id}&address_id=$address_id&goods_id=".$goods_id;
$chooseTakeType2Url = "plugin.php?id=tom_pintuan&mod=buy&showwxpaytitle=1&take_type=2&tstatus={$tstatus}&tlevel={$tlevel}&tuan_id={$tuan_id}&address_id=$address_id&goods_id=".$goods_id;

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_pintuan:buy");  

?>
