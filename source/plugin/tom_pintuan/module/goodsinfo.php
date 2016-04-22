<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$goods_id = isset($_GET['goods_id'])? intval($_GET['goods_id']):0;

$goodsInfo = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_by_id($goods_id);

if(!preg_match('/^http/', $goodsInfo['goods_pic']) ){
    $goods_pic = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$goodsInfo['goods_pic'];
}else{
    $goods_pic = $goodsInfo['goods_pic'];
}

if(!preg_match('/^http/', $value['pics1']) ){
    $pics1 = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$goodsInfo['pics1'];
}else{
    $pics1 = $goodsInfo['pics1'];
}

if(!preg_match('/^http/', $value['pics2']) ){
    $pics2 = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$goodsInfo['pics2'];
}else{
    $pics2 = $goodsInfo['pics2'];
}

if(!preg_match('/^http/', $value['pics3']) ){
    $pics3 = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$goodsInfo['pics3'];
}else{
    $pics3 = $goodsInfo['pics3'];
}

$content = stripslashes($goodsInfo['content']);

$shopInfo = C::t('#tom_pintuan#tom_pintuan_shop')->fetch_by_id($goodsInfo['shop_id']);
$shop_logo = '';
$sun_sales_num = 0;
if($shopInfo){
   if(!preg_match('/^http/', $shopInfo['logo']) ){
        $shop_logo = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$shopInfo['logo'];
    }else{
        $shop_logo = $shopInfo['logo'];
    }
    $sun_sales_num = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_all_sun_sales_num(" AND shop_id={$goodsInfo['shop_id']} ");
}
$listgoods_num = $pintuanConfig['yihe_tuanlistnum'];//意合拼团2015-11-22 已成团列表
$tuanListTmp = C::t('#tom_pintuan#tom_pintuan_tuan')->fetch_all_list(" AND goods_id={$goods_id} AND tuan_status=2  ","ORDER BY tuan_time DESC",0,$listgoods_num);
$tuanList = array();
if(is_array($tuanListTmp) && !empty($tuanListTmp)){
    foreach ($tuanListTmp as $key => $value){
        $tuanList[$key] = $value;
        
        $goodsInfoTmp = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_by_id($value['goods_id']);
        if($value['tlevel'] == 2){
            $goodsInfoTmp['tuan_num'] = $goodsInfoTmp['tuan_num_2'];
            $goodsInfoTmp['tuan_price'] = $goodsInfoTmp['tuan_price_2'];
        }else if($value['tlevel'] == 3){
            $goodsInfoTmp['tuan_num'] = $goodsInfoTmp['tuan_num_3'];
            $goodsInfoTmp['tuan_price'] = $goodsInfoTmp['tuan_price_3'];
        }
        $tuanList[$key]['tuan_price'] = $goodsInfoTmp['tuan_price'];
        $tuanList[$key]['tuan_time'] = dgmdate($value['tuan_time'], 'Y-m-d H:i:s',$tomSysOffset);
        $tuanList[$key]['tuanUrl'] = "plugin.php?id=tom_pintuan&mod=tuan&tlevel={$value['tlevel']}&tuan_id=".$value['id'];
        
        $userInfoTmp = C::t('#tom_pintuan#tom_pintuan_user')->fetch_by_id($value['user_id']);
        $userInfoTmp['nickname'] = cutstr($userInfoTmp['nickname'],8,"");
        $tuanList[$key]['userInfo'] = $userInfoTmp;
        
        $tuanTeamListTmp = C::t('#tom_pintuan#tom_pintuan_tuan_team')->fetch_all_list(" AND tuan_id={$value['id']} AND type_id=2 ","ORDER BY add_time ASC",0,500);
        $tuanTeamList = array();
        $tuanTeamListCount = 1;
        if(is_array($tuanTeamListTmp) && !empty($tuanTeamListTmp)){
            foreach ($tuanTeamListTmp as $k => $v){
                $ordersinfoTmp = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($v['order_id']);
                if($ordersinfoTmp['order_status'] == 2 || $ordersinfoTmp['order_status'] == 3 || $ordersinfoTmp['order_status'] == 4 || $ordersinfoTmp['order_status'] == 5){
                    $tuanTeamListCount++;
                }
            }
        }
        $tuanList[$key]['shengyuTuanTeamNum'] = $goodsInfoTmp['tuan_num'] - $tuanTeamListCount;
        
    }
}

//意合工作室更新20151023
if($subscribeFlag == 2 && $pintuanConfig['yihe_followtx'] == 1){
    $showGuanzuBox = 1;
}
//end意合工作室更新20151023

$content = stripslashes($goodsInfo['content']);

$tuanBuyUrl = "plugin.php?id=tom_pintuan&mod=buy&tstatus=1&tlevel=1&showwxpaytitle=1&goods_id=".$goods_id;
$tuan2BuyUrl = "plugin.php?id=tom_pintuan&mod=buy&tstatus=1&tlevel=2&showwxpaytitle=1&goods_id=".$goods_id;
$tuan3BuyUrl = "plugin.php?id=tom_pintuan&mod=buy&tstatus=1&tlevel=3&showwxpaytitle=1&goods_id=".$goods_id;
$oneBuyUrl = "plugin.php?id=tom_pintuan&mod=buy&tstatus=3&showwxpaytitle=1&goods_id=".$goods_id;

$sale_num = $goodsInfo['sales_num']+$goodsInfo['virtual_sales_num'];

$laTuanNum = $goodsInfo['tuan_num'] - 1;

$shareTitle = $goodsInfo['name'];
$shareUrl   = $_G['siteurl']."plugin.php?id=tom_pintuan&mod=goodsinfo&goods_id=".$goods_id;
$shareLogo  = $goods_pic;

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_pintuan:goodsinfo");  

?>
