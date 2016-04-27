<?php
if(!defined('IN_DISCUZ')){
	exit('Access Denied');
}

$moduleAction  = isset($_GET['moduleAction'])? trim($_GET['moduleAction']):'';

if(!checkChar($moduleAction)){
    exit('Access Denied');
}

$moduleActionFile = DISCUZ_ROOT.'./source/plugin/tom_weixin/module/front/'.$moduleAction.'.php';

if(file_exists($moduleActionFile)){
    include $moduleActionFile;
}else{
    exit('Action Denied No Module');
}

function checkChar($str = ''){
    $flag = false;
    if ($str && ereg("^[a-zA-Z0-9_]+$", $str)){
        $flag = true;
    }
    return  $flag;				
}

?>
