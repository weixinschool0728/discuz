<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$hookKeyword = $keyword;

$exitHookScript = false;

$hookList = C::t('#tom_weixin#tom_weixin_hook')->fetch_all_by_typeid("9");

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

$hookList = C::t('#tom_weixin#tom_weixin_hook')->fetch_all_by_typeid("10");

if(is_array($hookList) && !empty($hookList) && !$exitHookScript){
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

$hookList = C::t('#tom_weixin#tom_weixin_hook')->fetch_all_by_typeid("11");

if(is_array($hookList) && !empty($hookList) && !$exitHookScript){
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

$hookList = C::t('#tom_weixin#tom_weixin_hook')->fetch_all_by_typeid("12");

if(is_array($hookList) && !empty($hookList) && !$exitHookScript){
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

$hookList = C::t('#tom_weixin#tom_weixin_hook')->fetch_all_by_typeid("16");

if(is_array($hookList) && !empty($hookList) && !$exitHookScript){
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

$hookList = C::t('#tom_weixin#tom_weixin_hook')->fetch_all_by_typeid("17");

if(is_array($hookList) && !empty($hookList) && !$exitHookScript){
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

$hookList = C::t('#tom_weixin#tom_weixin_hook')->fetch_all_by_typeid("13");

if(is_array($hookList) && !empty($hookList) && !$exitHookScript){
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

$hookList = C::t('#tom_weixin#tom_weixin_hook')->fetch_all_by_typeid("14");

if(is_array($hookList) && !empty($hookList) && !$exitHookScript){
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

$hookList = C::t('#tom_weixin#tom_weixin_hook')->fetch_all_by_typeid("18");

if(is_array($hookList) && !empty($hookList) && !$exitHookScript){
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

$hookList = C::t('#tom_weixin#tom_weixin_hook')->fetch_all_by_typeid("19");

if(is_array($hookList) && !empty($hookList) && !$exitHookScript){
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

$hookList = C::t('#tom_weixin#tom_weixin_hook')->fetch_all_by_typeid("20");

if(is_array($hookList) && !empty($hookList) && !$exitHookScript){
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
