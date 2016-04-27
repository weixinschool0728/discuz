<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

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

$allCount = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_count(" AND user_id={$__UserInfo['id']} ");
$daifukuanCount = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_count(" AND user_id={$__UserInfo['id']} AND order_status=1 ");
$daishouhuoCount = C::t('#tom_pintuan#tom_pintuan_order')->fetch_all_count(" AND user_id={$__UserInfo['id']} AND order_status=4 ");


$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;
include template("tom_pintuan:personal");  

?>
