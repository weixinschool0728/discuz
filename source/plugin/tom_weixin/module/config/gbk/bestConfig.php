<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$moduleConfig = array(
    'module_cmd'       => "best",
    'module_desc'      => "��̳������",
	'power_id'         => '0',
	'module_ver'       => '1.0',
);

$moduleLang = array(
    'no_best_msg' => 'û�о�����',
);

$moduleSettingExt = array(
    array(
        'type'   => 'input',
        'title'  => '��ʾ��������',
        'name'   => 'num',
        'value'  => '10',
        'desc'   => '���������б���������������ܴ���10��',
    ),
);

?>