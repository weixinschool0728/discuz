<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$openid     = isset($_GET['openid'])? trim($_GET['openid']):'';
$dreferer   = isset($_GET['dreferer'])? base64_decode($_GET['dreferer']):$_G['siteurl'];
$userInfo = C::t('#tom_weixin#tom_weixin_user')->fetch_one_by_openid($openid);

$params['mod'] = 'login';
tom_connect_login($userInfo['uid']);
loadcache('usergroups');
C::t('common_member_status')->update($userInfo['uid'], array('lastip'=>$_G['clientip'], 'lastvisit'=>TIMESTAMP, 'lastactivity' => TIMESTAMP));

$username = $userInfo['username'];
$avatarUrl = avatar($userInfo['uid'],'middle',TRUE);

if (CHARSET == 'gbk') {
    include template("tom_weixin:login_gbk");
}else{
    include template("tom_weixin:login_utf8");
}

function tom_connect_login($uid) {
	global $_G;

	if(!($member = getuserbyuid($uid, 1))) {
		return false;
	} else {
		if(isset($member['_inarchive'])) {
			C::t('common_member_archive')->move_to_master($member['uid']);
		}
	}

	require_once libfile('function/member');
	$cookietime = 1296000;
	setloginstatus($member, $cookietime);

//	dsetcookie('connect_login', 1, $cookietime);
	return true;
}
?>
