<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$moduleConfig = array(
    'module_cmd'       => "bind",
    'module_desc'      => "绑定论坛账号",
	'power_id'         => '0',
	'module_ver'       => '1.0',
);

$moduleLang = array(
    'uidusername'  => '请输入用户名或UID',
    'password'     => '请输入论坛登录密码',
    'againpassword'=> "密码错误：请重新输入密码",
    'nouidusername'=> "UID或用户名不存在\n请重新输入",
    'bind_succeed' => '成功绑定论坛帐号',
	'isbind_error' => '您已经绑定过论坛帐号，请勿重复操作',
);

$moduleSettingExt = array();

?>