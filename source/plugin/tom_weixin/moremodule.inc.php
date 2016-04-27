<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

include DISCUZ_ROOT.'./source/plugin/tom_weixin/core/module.class.php';
$moduleClass = new tom_module();
$allModuleList = $moduleClass->getAllList();
$installModuleIdStr = "";
if(is_array($allModuleList) && !empty($allModuleList)){
    $installModuleIdStr = implode("_", $allModuleList);
}

include DISCUZ_ROOT.'./source/plugin/tom_weixin/cloud/config.php';
$urlBaseUrl = $_G['siteurl'].ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_weixin&pmod='; 
dheader('location:'.$TOMCLOUDHOST.'/api/module.php?ver=40&addonId=tom_weixin&moduleIdStr='.$installModuleIdStr.'&baseUrl='.  urlencode($urlBaseUrl));
?>