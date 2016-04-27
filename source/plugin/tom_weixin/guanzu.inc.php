<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
loadcache('plugin');
$tomConfig = $_G['cache']['plugin']['tom_weixin'];
$erweima = $tomConfig['ewm_pic'];
include template("tom_weixin:guanzu");
?>
