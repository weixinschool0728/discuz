<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
include DISCUZ_ROOT.'./source/plugin/tom_weixin/cloud/config.php';
$urlBaseUrl = $_G['siteurl'].ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_weixin&pmod='; 
dheader('location:'.$TOMCLOUDHOST.'/api/addon.php?ver=40&addonId=tom_weixin&baseUrl='.  urlencode($urlBaseUrl));
?>