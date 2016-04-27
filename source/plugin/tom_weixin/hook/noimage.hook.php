<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$hookList = C::t('#tom_weixin#tom_weixin_hook')->fetch_all_by_typeid("15");

if(is_array($hookList) && !empty($hookList)){
    foreach ($hookList as $key => $value){
        $exitHookScript = false;
        $hookScriptName = DISCUZ_ROOT.$value['hook_script'];
        if(file_exists($hookScriptName)){
            include $hookScriptName;
        }
        if($exitHookScript){
            break;
        }
    }
}

?>
