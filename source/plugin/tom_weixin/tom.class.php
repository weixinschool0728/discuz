<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class plugin_tom_weixin {

    function  global_nav_extra(){ 
		global $_G;
        $tomConfig = $_G['cache']['plugin']['tom_weixin'];
        
        $return = '';
        if($tomConfig['guanzu_btn2'] == 1){
            $return = <<<EOF
    <a style="float: right;display: inline;margin-top: 5px;margin-left: 3px;margin-right: 3px;" href="javascript:;" onclick="showWindow('tom_weixin_guanzu','plugin.php?id=tom_weixin:guanzu','get',0);return false;">
    <img src="source/plugin/tom_weixin/images/wx_guanzhu.gif">
    </a>
EOF;
        }
		return $return;
	}
    function  global_cpnav_extra1(){ 
		global $_G;
        $tomConfig = $_G['cache']['plugin']['tom_weixin'];
        $return = '';
        if($tomConfig['guanzu_btn1'] == 1){
            $return = <<<EOF
    <a style="display: inline;margin-top: 0px;margin-left: 3px;margin-right: 3px;" href="javascript:;" onclick="showWindow('tom_weixin_guanzu','plugin.php?id=tom_weixin:guanzu','get',0);return false;">
    <img src="source/plugin/tom_weixin/images/wx_guanzhu.gif">
    </a>
EOF;
        }
		return $return;
	}
}

class plugin_tom_weixin_forum {
    
    function index_status_extra() {
		global $_G;
        $tomConfig = $_G['cache']['plugin']['tom_weixin'];
        $return = '';
        if($tomConfig['guanzu_btn3'] == 1){
            $return = <<<EOF
    <a style="float: left;height: 24px;display: inline;padding:4px 0 0 0;margin-left: 3px;margin-right: 3px;" href="javascript:;" onclick="showWindow('tom_weixin_guanzu','plugin.php?id=tom_weixin:guanzu','get',0);return false;">
    <img src="source/plugin/tom_weixin/images/wx_guanzhu.jpg">
    </a>
EOF;
        }
        
		return $return;
    }
    
    function index_nav_extra() {
        global $_G;
        $tomConfig = $_G['cache']['plugin']['tom_weixin'];
        $return = '';
        if($tomConfig['guanzu_btn4'] == 1){
            $wxGuanzhu = lang('plugin/tom_weixin','wx_guanzhu');
        $return = <<<EOF
    <a class="xi2" style="padding-left: 20px;background-image: url(source/plugin/tom_weixin/images/wx_guanzhu2.gif); background-repeat: no-repeat;background-position: 0px -2px;  " href="javascript:;" onclick="showWindow('tom_weixin_guanzu','plugin.php?id=tom_weixin:guanzu','get',0);return false;">
    {$wxGuanzhu}
    </a><span class="pipe">|</span>
EOF;
        }
		return $return;
    }
    
}

?>