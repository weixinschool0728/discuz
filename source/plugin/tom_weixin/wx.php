<?php
if($_SERVER['REQUEST_METHOD'] == 'POST' || $_GET['echostr']){
    define('IN_WEIXINAPP', true); 
}else{
    exit('Access Denied');
}
define('APPTYPEID', 127);
define('CURSCRIPT', 'plugin');
define('DISABLEXSSCHECK', true); 

$_GET['id'] = 'tom_weixin';

require substr(dirname(__FILE__), 0, -25).'/source/class/class_core.php';

$discuz = C::app();
$cachelist = array('plugin', 'diytemplatename');

$discuz->cachelist = $cachelist;
$discuz->init();

define('CURMODULE', 'tom_weixin');

$_G['siteurl'] = substr($_G['siteurl'], 0, -25);
$_G['siteroot'] = substr( $_G ['siteroot'], 0, - 25);

include DISCUZ_ROOT . './source/plugin/tom_weixin/tom_weixin.inc.php';

?>
