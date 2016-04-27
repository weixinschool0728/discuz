<?php
if (!defined('IN_DISCUZ') || !defined('IN_WEIXINAPP')) {
    exit('Access Denied');
}

$tomConfig = $_G['cache']['plugin']['tom_weixin'];

define("TOM_SITEURL", $_G['siteurl']);
define("TOM_TOKEN", $tomConfig['wx_token']);
define("TOM_AUTO_LOGIN", $tomConfig['auto_login']);
define("TOM_TOKEN_CHECK", $tomConfig['token_check']);
define("TOM_ICOVN", 0);

include DISCUZ_ROOT.'./source/plugin/tom_weixin/core/tomwx.class.php';
include DISCUZ_ROOT.'./source/plugin/tom_weixin/core/weixin.class.php';
include DISCUZ_ROOT.'./source/plugin/tom_weixin/core/module.class.php';
include DISCUZ_ROOT.'./source/plugin/tom_weixin/core/activity.class.php';
include DISCUZ_ROOT.'./source/plugin/tom_weixin/core/user.class.php';
include DISCUZ_ROOT.'./source/plugin/tom_weixin/function/common.php';
$weixinObj   = new weixinCallbackApi();
$moduleClass = new tom_module();
$userClass   = new tom_user();
if (isset($_GET['echostr'])) {
    $weixinObj->check();
} else {
    $weixinObj->runCheck();
}

?>
