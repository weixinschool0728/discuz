<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE IF EXISTS pre_tom_weixin_activity;
DROP TABLE IF EXISTS pre_tom_weixin_hook;
DROP TABLE IF EXISTS pre_tom_weixin_log;
DROP TABLE IF EXISTS pre_tom_weixin_module;
DROP TABLE IF EXISTS pre_tom_weixin_plugin;
DROP TABLE IF EXISTS pre_tom_weixin_user;
DROP TABLE IF EXISTS pre_tom_weixin_subuser;

EOF;

runquery($sql);

$finish = TRUE;

?>