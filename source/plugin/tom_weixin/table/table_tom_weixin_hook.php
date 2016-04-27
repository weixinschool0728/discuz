<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
} 

class table_tom_weixin_hook extends discuz_table{
	public function __construct() {
        parent::__construct();
		$this->_table = 'tom_weixin_hook';
		$this->_pk    = 'id';
	}

    public function fetch_all_by_typeid($typeid,$field='*') {
		return DB::fetch_all("SELECT $field FROM %t WHERE hook_type=%d ORDER BY id DESC", array($this->_table, $typeid));
	}
	
	public function delete_by_obj_id_type($objid,$objtype) {
		return DB::query("DELETE FROM %t WHERE obj_id=%s AND obj_type=%d ", array($this->_table, $objid,$objtype));
	}
    
}


?>
