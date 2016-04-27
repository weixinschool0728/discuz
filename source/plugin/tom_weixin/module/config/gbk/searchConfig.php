<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$moduleConfig = array(
    'module_cmd'       => "search",
    'module_desc'      => "帖子搜索",
	'power_id'         => '0',
	'module_ver'       => '1.3',
);

$moduleLang = array(
    'search_msg' => "请输入要查询的关键词：",
    'nobook_msg' => "没有关于‘{keyword}’的帖子",
);

$moduleSettingExt = array(
    array(
        'type'   => 'input',
        'title'  => '允许搜索论坛板块ID',
        'name'   => 'fid',
        'value'  => '0',
        'desc'   => '填写允许搜索论坛板块ID，多版块用“英文”逗号隔开：1,2,3 （留空则全部板块）',
    ),
    array(
        'type'   => 'input',
        'title'  => '显示帖子数量',
        'name'   => 'num',
        'value'  => '10',
        'desc'   => '设置帖子列表的帖子数量，不能大于10条',
    ),
    array(
        'type'   => 'radio',
        'title'  => '只显示图片贴',
        'name'   => 'is_image',
        'value'  => '0',
        'desc'   => '是否只显示有图片附件的帖子；无图时使用随机图片：[<a href="http://addon.discuz.com/?@tom_weixin.plugin.40833" target="_blank"><font color="#FF0000">安装帖子列表随机图片组件</font></a>]',
        'item'   => array(
            '1' => "是",
            '0' => "否",
        ),
    ),
    array(
        'type'   => 'radio',
        'title'  => '是否开启直接搜索',
        'name'   => 'is_hook',
        'value'  => '1',
        'desc'   => '开启直接搜索后，无需发送搜索关键词指令，就可以直接搜索',
        'item'   => array(
            '1' => "开启",
            '0' => "关闭",
        ),
    ),
);

?>