<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$moduleConfig = array(
    'module_cmd'       => "search",
    'module_desc'      => "��������",
	'power_id'         => '0',
	'module_ver'       => '1.3',
);

$moduleLang = array(
    'search_msg' => "������Ҫ��ѯ�Ĺؼ��ʣ�",
    'nobook_msg' => "û�й��ڡ�{keyword}��������",
);

$moduleSettingExt = array(
    array(
        'type'   => 'input',
        'title'  => '����������̳���ID',
        'name'   => 'fid',
        'value'  => '0',
        'desc'   => '��д����������̳���ID�������á�Ӣ�ġ����Ÿ�����1,2,3 ��������ȫ����飩',
    ),
    array(
        'type'   => 'input',
        'title'  => '��ʾ��������',
        'name'   => 'num',
        'value'  => '10',
        'desc'   => '���������б���������������ܴ���10��',
    ),
    array(
        'type'   => 'radio',
        'title'  => 'ֻ��ʾͼƬ��',
        'name'   => 'is_image',
        'value'  => '0',
        'desc'   => '�Ƿ�ֻ��ʾ��ͼƬ���������ӣ���ͼʱʹ�����ͼƬ��[<a href="http://addon.discuz.com/?@tom_weixin.plugin.40833" target="_blank"><font color="#FF0000">��װ�����б����ͼƬ���</font></a>]',
        'item'   => array(
            '1' => "��",
            '0' => "��",
        ),
    ),
    array(
        'type'   => 'radio',
        'title'  => '�Ƿ���ֱ������',
        'name'   => 'is_hook',
        'value'  => '1',
        'desc'   => '����ֱ�����������跢�������ؼ���ָ��Ϳ���ֱ������',
        'item'   => array(
            '1' => "����",
            '0' => "�ر�",
        ),
    ),
);

?>