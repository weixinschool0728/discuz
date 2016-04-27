<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

// Install
function moduleInstall(){
    global $_G,$moduleConfig;
    require_once libfile('function/plugin');
    
    $hookData = array();
    $hookData['hook_type'] = '10';
    $hookData['hook_script'] = './source/plugin/tom_weixin/module/hook/replyHook.php';
    $hookData['obj_id'] = 'reply';
    $hookData['obj_type'] = '1';
    C::t('#tom_weixin#tom_weixin_hook')->insert($hookData);
    
$sql = <<<EOF

DROP TABLE IF EXISTS `pre_tom_weixin_reply`;
CREATE TABLE `pre_tom_weixin_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reply_cmd` varchar(100) NOT NULL,
  `reply_type` tinyint(4) NOT NULL DEFAULT '1',
  `reply_desc` varchar(100) NOT NULL,
  `reply_text` text,
  `part1` varchar(255) DEFAULT NULL,
  `part2` varchar(255) DEFAULT NULL,
  `part3` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

EOF;

    runquery($sql);
    
    return;
}

// Uninstall
function moduleUninstall(){
    global $_G,$moduleConfig;
    require_once libfile('function/plugin');
    
    C::t('#tom_weixin#tom_weixin_hook')->delete_by_obj_id_type("reply","1");
    
$sql = <<<EOF

DROP TABLE IF EXISTS pre_tom_weixin_reply;

EOF;

    runquery($sql);

    return;
}

// Upgrade
function moduleUpgrade(){
    global $_G,$moduleConfig;
    require_once libfile('function/plugin');
    
    C::t('#tom_weixin#tom_weixin_hook')->delete_by_obj_id_type("reply","1");
    $hookData = array();
    $hookData['hook_type'] = '10';
    $hookData['hook_script'] = './source/plugin/tom_weixin/module/hook/replyHook.php';
    $hookData['obj_id'] = 'reply';
    $hookData['obj_type'] = '1';
    C::t('#tom_weixin#tom_weixin_hook')->insert($hookData);
    return;
}

?>
