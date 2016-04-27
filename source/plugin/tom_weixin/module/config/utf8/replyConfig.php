<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$moduleConfig = array(
    'module_cmd'       => "reply",
    'module_desc'      => "超级自动回复",
	'power_id'         => '0',
	'module_ver'       => '1.2',
    'admin'            => '1',
    'admin_name'       => '添加回复',
    'is_menu'          => '2',
);

$moduleLang = array(
    'reply_help_title' => '自动回复设置帮助',
    'reply_help_1'     => '超级自动回复不支持<b>多图文、多指令、模糊匹配</b>，需要使用<b>多图文、多指令、模糊匹配</b>自动回复 请安装：[<a href="http://addon.discuz.com/?@tom_weixin.plugin.40832" target="_blank"><font color="#FF0000">多功能图文菜单组件</font></a>]',
    
    'reply_list_title'  => '自动回复列表',
    'reply_list'        => '回复列表管理',
    'reply_list_back'   => '<<< 返回列表管理',
    'reply_edit'        => '编辑',
    'reply_cmd'         => '回复指令',
	'reply_type'        => '指令类型',
    'reply_desc'        => '指令描述',
    'add_wb'            => '添加文本回复',
    'add_tw'            => '添加图文回复',
    'add_music'         => '添加音乐回复',
    'wb_txt'            => '回复内容',
    'music_title'       => '音乐标题',
    'music_txt'         => '音乐描述',
    'music_url'         => '音乐链接',
    'music_url_msg'     => '例如：http://xxxx.com/music/music.mp3',
    'tw_title'          => '图文标题',
    'tw_txt'            => '图文描述',
    'tw_src'            => '图片地址',
    'tw_url'            => '图文链接',
    'txt_msg'           => '换行请用{n}代替,如：第一行{n}第二行{n}第三行，（文本类型回复）添加链接可以直接使用html a 标签',
    'wb'                => '文本',
    'tw'                => '图文',
    'music'             => '音乐',
);

$moduleSettingExt = array();

?>