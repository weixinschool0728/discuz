<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$moduleConfig = array(
    'module_cmd'       => "best",
    'module_desc'      => "论坛精华帖",
	'power_id'         => '0',
	'module_ver'       => '1.0',
);

$moduleLang = array(
    'no_best_msg' => '没有精华帖',
);

$moduleSettingExt = array(
    array(
        'type'   => 'input',
        'title'  => '显示帖子数量',
        'name'   => 'num',
        'value'  => '10',
        'desc'   => '设置帖子列表的帖子数量，不能大于10条',
    ),
);

?>