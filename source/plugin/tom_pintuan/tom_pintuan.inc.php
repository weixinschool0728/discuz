<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

session_start();
define('TPL_DEFAULT', true);
$formhash = FORMHASH;
$pintuanConfig = $_G['cache']['plugin']['tom_pintuan'];
$tomSysOffset = getglobal('setting/timeoffset');
$nowDayTime = gmmktime(0,0,0,dgmdate($_G['timestamp'], 'n',$tomSysOffset),dgmdate($_G['timestamp'], 'j',$tomSysOffset),dgmdate($_G['timestamp'], 'Y',$tomSysOffset)) - $tomSysOffset*3600;
require_once libfile('function/discuzcode');
$uSiteUrl = urlencode($_G['siteurl']);
$appid = trim($pintuanConfig['wxpay_appid']);  
$appsecret = trim($pintuanConfig['wxpay_appsecret']);

include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/weixin.class.php';
$weixinClass = new weixinClass($appid,$appsecret);

$wxJssdkConfig = array();
$wxJssdkConfig["appId"]     = "";
$wxJssdkConfig["timestamp"] = time();
$wxJssdkConfig["nonceStr"]  = "";
$wxJssdkConfig["signature"] = "";
if($pintuanConfig['open_wx_share'] == 1){
    $wxJssdkConfig = $weixinClass->get_jssdk_config();
}
$shareTitle = $pintuanConfig['wx_share_title'];
$shareDesc  = $pintuanConfig['wx_share_desc'];
$shareUrl   = $_G['siteurl']."plugin.php?id=tom_pintuan&mod=index";
$shareLogo  = $pintuanConfig['wx_share_pic'];

$__UserInfo = array();
$userStatus = false;
$cookieOpenid = getcookie('tom_pintuan_user_openid');
if(!empty($cookieOpenid)){
    $__UserInfo = C::t('#tom_pintuan#tom_pintuan_user')->fetch_by_openid($cookieOpenid);
    if($__UserInfo && !empty($__UserInfo['openid'])){
        $userStatus = true;
    }
}

if(!$userStatus){
    $openid     = '';
    $nickname   = '';
    $headimgurl = '';
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/oauth3.php';
    $nickname = diconv($nickname,'utf-8');
    $__UserInfo = C::t('#tom_pintuan#tom_pintuan_user')->fetch_by_openid($openid);
    if($__UserInfo){
        $updateData = array();
        $updateData['nickname']    = $nickname;
        $updateData['picurl']     = $headimgurl;
        C::t('#tom_pintuan#tom_pintuan_user')->update($__UserInfo['id'],$updateData);
        $lifeTime = 86400;
        dsetcookie('tom_pintuan_user_openid',$openid,$lifeTime);
    }else{
        $insertData = array();
        $insertData['openid']      = $openid;
        $insertData['nickname']    = $nickname;
        $insertData['picurl']      = $headimgurl;
        $insertData['add_time']    = TIMESTAMP;
        if(C::t('#tom_pintuan#tom_pintuan_user')->insert($insertData)){
            $__UserInfo = C::t('#tom_pintuan#tom_pintuan_user')->fetch_by_openid($openid);
            $lifeTime = 86400;
            dsetcookie('tom_pintuan_user_openid',$openid,$lifeTime);
        }
    }
}

if (CHARSET == 'gbk') {
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/config/config.gbk.php';
}else{
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/config/config.utf8.php';
}

if($_GET['mod'] == 'index'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/module/index.php';
    
}else if($_GET['mod'] == 'goodsinfo'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/module/goodsinfo.php';
    
}else if($_GET['mod'] == 'groups'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/module/groups.php';
    
}else if($_GET['mod'] == 'orders'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/module/orders.php';
    
}else if($_GET['mod'] == 'personal'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/module/personal.php';
    
}else if($_GET['mod'] == 'address'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/module/address.php';
    
}else if($_GET['mod'] == 'tuan'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/module/tuan.php';
    
}else if($_GET['mod'] == 'ordersinfo'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/module/ordersinfo.php';
    
}else if($_GET['mod'] == 'buy'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/module/buy.php';
    
}else if($_GET['mod'] == 'tuanrule'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/module/tuanrule.php';
    
}else if($_GET['mod'] == 'ordersqrcode'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/module/ordersqrcode.php';
    
}else if($_GET['mod'] == 'orderssure'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/module/orderssure.php';
    
}else if($_GET['mod'] == 'cates'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/module/cates.php';
    
}else if($_GET['mod'] == 'shop'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/module/shop.php';
    
}else if($_GET['mod'] == 'search'){
    
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/module/search.php';
    
}else{
    
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/module/index.php';
}

?>
