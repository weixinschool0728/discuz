<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$TOMCLOUDHOST = "http://discuzapi.tomwx.net";
$urlBaseUrl = $_G['siteurl'].ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_pintuan&pmod='; 
dheader('location:'.$TOMCLOUDHOST.'/api/addon.php?ver=40&addonId=tom_pintuan&baseUrl='.urlencode($urlBaseUrl));
?>