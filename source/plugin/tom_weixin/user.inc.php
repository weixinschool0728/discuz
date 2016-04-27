<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$tomScriptLang = $scriptlang['tom_weixin'];
include DISCUZ_ROOT.'./source/plugin/tom_weixin/core/user.class.php';
$userClass = new tom_user();
$act = isset($_GET['act'])? $_GET['act']:'';
$formhash =  $_GET['formhash']? $_GET['formhash']:'';
$userInfo = array();
if ($formhash == FORMHASH && $act == 'unbind'){
    $userClass->deleteByOpenid($_GET['openid']);
    cpmsg($tomScriptLang['unbind_succ'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=tom_weixin&pmod=user', 'succeed');
}else{
    $pagesize = 15;
	$page = intval($_GET['page'])>0? intval($_GET['page']):1;
	$start = ($page-1)*$pagesize;	
    $count = $userClass->getCount();
	$userList = $userClass->getList('',"ORDER BY bind_time DESC",$start,$pagesize);
	showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $tomScriptLang['user_list_title'] . '</th></tr>';
    echo '<tr class="header">';
    echo '<th>' . $tomScriptLang['user_uid'] . '</th>';
    echo '<th>' . $tomScriptLang['user_username'] . '</th>';
    echo '<th>' . $tomScriptLang['user_time'] . '</th>';
    echo '<th>' . $tomScriptLang['handle'] . '</th>';
    echo '</tr>';
	foreach ($userList as $key => $value){
        $bindTime = date("Y-m-d",$value['bind_time']);
        echo '<tr>';
        echo '<td>' . $value['uid'] . '</td>';
        echo '<td> <a href="home.php?mod=space&uid='.$value['uid'].'"target="_blank" >' . $value['username'] . '</a></td>';
        echo '<td>' . $bindTime . '</td>';
        echo '<td>';
        echo '<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=tom_weixin&pmod=user&act=unbind&openid='.$value['openid'].'&formhash='.FORMHASH.'">' . $tomScriptLang['unbind'] . '</a>';
        echo '</td>';
        echo '</tr>';
	}
	showtablefooter();
	$multi = multi($count, $pagesize, $page, ADMINSCRIPT."?action=plugins&operation=config&do=".$pluginid."&identifier=tom_weixin&pmod=user");	
	showsubmit('', '', '', '', $multi, false);
}

?>