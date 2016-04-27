<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$tuan_id = isset($_GET['tuan_id'])? intval($_GET['tuan_id']):0;

$tuanInfo = C::t('#tom_pintuan#tom_pintuan_tuan')->fetch_by_id($tuan_id);
$goodsInfo = C::t('#tom_pintuan#tom_pintuan_goods')->fetch_by_id($tuanInfo['goods_id']);
if(!preg_match('/^http/', $goodsInfo['goods_pic']) ){
    $goods_pic = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$goodsInfo['goods_pic'];
}else{
    $goods_pic = $goodsInfo['goods_pic'];
}
$goodsInfo['goods_pic'] = $goods_pic;

$tuanTop = C::t('#tom_pintuan#tom_pintuan_user')->fetch_by_id($tuanInfo['user_id']);
$tuanTimes = dgmdate($tuanInfo['tuan_time'], 'Y-m-d H:i:s',$tomSysOffset);

$tuanTeamListTmp = C::t('#tom_pintuan#tom_pintuan_tuan_team')->fetch_all_list(" AND tuan_id={$tuan_id} AND type_id=2 ","ORDER BY add_time ASC",0,500);
$tuanTeamList = array();
$tuanTeamListCount = 1;
if(is_array($tuanTeamListTmp) && !empty($tuanTeamListTmp)){
    foreach ($tuanTeamListTmp as $key => $value){
        $ordersinfoTmp = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($value['order_id']);
        if($ordersinfoTmp['order_status'] == 2 || $ordersinfoTmp['order_status'] == 3 || $ordersinfoTmp['order_status'] == 4 || $ordersinfoTmp['order_status'] == 5){
            $tuanTeamListCount++;
            $tuanTeamList[$key] = $value;
            $userInfoTmp = C::t('#tom_pintuan#tom_pintuan_user')->fetch_by_id($value['user_id']);
            $tuanTeamList[$key]['userInfo'] = $userInfoTmp;
            $tuanTeamList[$key]['add_time'] = dgmdate($value['add_time'], 'Y-m-d H:i:s',$tomSysOffset);
        }
    }
}

if($tuanInfo['tlevel'] == 2){
    $goodsInfo['tuan_num'] = $goodsInfo['tuan_num_2'];
    $goodsInfo['tuan_price'] = $goodsInfo['tuan_price_2'];
}else if($tuanInfo['tlevel'] == 3){
    $goodsInfo['tuan_num'] = $goodsInfo['tuan_num_3'];
    $goodsInfo['tuan_price'] = $goodsInfo['tuan_price_3'];
}

$shengyuTuanTeamNum = 0;
if($goodsInfo['tuan_num'] > $tuanTeamListCount){
    $shengyuTuanTeamNum = $goodsInfo['tuan_num'] - $tuanTeamListCount;
}

$showTemplateSmsAjax = 0;
if($shengyuTuanTeamNum == 0 && $tuanInfo['tuan_status'] == 2){
    $tuanInfo['tuan_status'] = 3;
    $updateData = array();
    $updateData['tuan_status'] = 3;
    $updateData['success_time'] = TIMESTAMP;
    C::t('#tom_pintuan#tom_pintuan_tuan')->update($tuanInfo['id'],$updateData);
    C::t('#tom_pintuan#tom_pintuan_order')->update_tuan_status_by_tuan_id($tuanInfo['id'],3);
    $showTemplateSmsAjax = 1;
}
$templateSmsAjaxUrl = "plugin.php?id=tom_pintuan:api&act=tuan_ok_sms&tuan_id={$tuan_id}&formhash=".FORMHASH;

$addTuanUrl = "plugin.php?id=tom_pintuan&mod=buy&tstatus=2&tlevel={$tuanInfo['tlevel']}&showwxpaytitle=1&goods_id={$tuanInfo['goods_id']}&tuan_id={$tuan_id}";

$showBtnBox = 1;
if($__UserInfo['id'] == $tuanInfo['user_id']){
    $showBtnBox = 2;
}else{
    $tuanUserTeamTmp = C::t('#tom_pintuan#tom_pintuan_tuan_team')->fetch_by_tuan_user_id($tuan_id,$__UserInfo['id']);
    if($tuanUserTeamTmp){
        $orderInfo = C::t('#tom_pintuan#tom_pintuan_order')->fetch_by_id($tuanUserTeamTmp['order_id']);
        if($orderInfo && $orderInfo['order_status'] == 6){
            $showBtnBox = 4;
        }else{
            $showBtnBox = 3;
        }
    }else{
        $showBtnBox = 4;
    }
}

if($tuanInfo['tuan_status'] != 2){
    $showBtnBox = 5;
}

$tuanHours = $goodsInfo['tuan_hours'];
if(!empty($tuanInfo['prolong_hours'])){
    $tuanHours = $goodsInfo['tuan_hours']+$tuanInfo['prolong_hours'];
}
$tuanHours = intval($tuanHours);

$daojishiTimes = $tuanInfo['tuan_time']+$tuanHours*3600 - TIMESTAMP;
if($daojishiTimes <= 0 && $tuanInfo['tuan_status'] != 3){
    $tuanInfo['tuan_status'] = 4;
    $updateData = array();
    $updateData['tuan_status'] = 4;
    C::t('#tom_pintuan#tom_pintuan_tuan')->update($tuanInfo['id'],$updateData);
    C::t('#tom_pintuan#tom_pintuan_order')->update_tuan_status_by_tuan_id($tuanInfo['id'],4);
    $daojishiTimes = 0;
    $showBtnBox = 6;
}

$shareTitle = str_replace("{NUM}", $shengyuTuanTeamNum, $goodsInfo['share_title']);
$shareDesc  = str_replace("{NUM}", $shengyuTuanTeamNum, $goodsInfo['share_desc']);
$shareUrl   = $_G['siteurl']."plugin.php?id=tom_pintuan&mod=tuan&tuan_id=".$tuan_id;
if($pintuanConfig['tuan_share_pic'] == 1){
    $shareLogo  = $goodsInfo['goods_pic'];
}else{
    $shareLogo  = $__UserInfo['picurl'];
}

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_pintuan:tuan");  

?>
