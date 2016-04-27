<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_common_member_wechat extends discuz_table {

	public function __construct() {
		$this->_table = 'common_member_wechat';
		$this->_pk = 'uid';
		parent::__construct();
	}
    
    public function delete_by_openid($openid) {
		return DB::query("DELETE FROM %t WHERE isregister=0 AND openid=%s", array($this->_table, $openid));
	}


}
