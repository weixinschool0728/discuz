<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
include DISCUZ_ROOT.'./source/plugin/tom_weixin/cloud/config.php';
loadcache('plugin');
$tomConfig = $_G['cache']['plugin']['tom_weixin'];

$tomConfig['wx_appid'] = trim($tomConfig['wx_appid']);
$tomConfig['wx_appsecret'] = trim($tomConfig['wx_appsecret']);

$urlBaseUrl = $_G['siteurl'].ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_weixin&pmod='; 
dheader('location:'.$TOMCLOUDHOST.'/api/menu.php?ver=40&appId='.$tomConfig['wx_appid'].'&appSecret='.$tomConfig['wx_appsecret'].'&baseUrl='.  urlencode($urlBaseUrl));
?>