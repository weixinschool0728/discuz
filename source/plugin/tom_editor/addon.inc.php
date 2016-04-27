<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$TOMCLOUDHOST = "http://api.tom3g.cn";
$cloudConfigName = DISCUZ_ROOT.'./source/plugin/tom_weixin/cloud/config.php';
if(file_exists($cloudConfigName)){
    include $cloudConfigName;
} 
$urlBaseUrl = $_G['siteurl'].ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_editor&pmod='; 
dheader('location:'.$TOMCLOUDHOST.'/api/addon.php?ver=10&addonId=tom_editor&baseUrl='.  urlencode($urlBaseUrl));
?>