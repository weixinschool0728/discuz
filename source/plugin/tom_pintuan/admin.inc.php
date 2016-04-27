<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$Lang = $scriptlang['tom_pintuan'];
$adminBaseUrl = ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_pintuan&pmod=admin'; 
$adminListUrl = 'action=plugins&operation=config&do='.$pluginid.'&identifier=tom_pintuan&pmod=admin';
$adminFromUrl = 'plugins&operation=config&do=' . $pluginid . '&identifier=tom_pintuan&pmod=admin';
$uSiteUrl = urlencode($_G['siteurl']);

$tomSysOffset = getglobal('setting/timeoffset');

if (CHARSET == 'gbk') {
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/config/config.gbk.php';
}else{
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/config/config.utf8.php';
}

include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/tom.form.php';
include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/admin.core.php';
include DISCUZ_ROOT.'./source/plugin/tom_pintuan/class/tom.upload.php';
$pintuanConfig = get_pintuan_config($pluginid);

if($_GET['tmod'] == 'goods'){
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/admin/goods.php';
}else if($_GET['tmod'] == 'order'){
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/admin/order.php';
}else if($_GET['tmod'] == 'tuan'){
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/admin/tuan.php';
}else if($_GET['tmod'] == 'user'){
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/admin/user.php';
}else if($_GET['tmod'] == 'district'){
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/admin/district.php';
}else if($_GET['tmod'] == 'focuspic'){
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/admin/focuspic.php';
}else if($_GET['tmod'] == 'cate'){
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/admin/cate.php';
}else if($_GET['tmod'] == 'shop'){
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/admin/shop.php';
}else if($_GET['tmod'] == 'express'){
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/admin/express.php';
}else if($_GET['tmod'] == 'addon'){
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/admin/addon.php';
}else{
    include DISCUZ_ROOT.'./source/plugin/tom_pintuan/admin/goods.php';
}

?>
