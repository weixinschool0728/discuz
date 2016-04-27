<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$moduleConfig = array(
    'module_cmd'     => "unbind",
    'module_desc'    => "取消绑定论坛账号",
	'power_id'       => '1',
	'module_ver'     => '1.0',
);

$moduleLang = array(
    'unbind_success' => '成功取消绑定',
);

$moduleSettingExt = array();
?>