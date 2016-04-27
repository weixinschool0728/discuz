<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

// Install
function moduleInstall(){
    global $_G,$moduleConfig;
    require_once libfile('function/plugin');
    
    $hookData = array();
    $hookData['hook_type'] = '13';
    $hookData['hook_script'] = './source/plugin/tom_weixin/module/hook/searchHook.php';
    $hookData['obj_id'] = 'search';
    $hookData['obj_type'] = '1';
    C::t('#tom_weixin#tom_weixin_hook')->insert($hookData);
    
    
    return;
}

// Uninstall
function moduleUninstall(){
    global $_G,$moduleConfig;
    require_once libfile('function/plugin');
    
    C::t('#tom_weixin#tom_weixin_hook')->delete_by_obj_id_type("search","1");
    
    return;
}

// Upgrade
function moduleUpgrade(){
    global $_G,$moduleConfig;
    require_once libfile('function/plugin');
    C::t('#tom_weixin#tom_weixin_hook')->delete_by_obj_id_type("search","1");
    $hookData = array();
    $hookData['hook_type'] = '13';
    $hookData['hook_script'] = './source/plugin/tom_weixin/module/hook/searchHook.php';
    $hookData['obj_id'] = 'search';
    $hookData['obj_type'] = '1';
    C::t('#tom_weixin#tom_weixin_hook')->insert($hookData);
    
    
    return;
}

?>
