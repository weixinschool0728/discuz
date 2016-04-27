<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
} 

class table_tom_weixin_reply extends discuz_table{
	public function __construct() {
        parent::__construct();
		$this->_table = 'tom_weixin_reply';
		$this->_pk    = 'id';
	}

    public function fetch_by_id($id,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE id=%d", array($this->_table, $id));
	}
    
    public function fetch_by_replycmd($replycmd,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE reply_cmd=%s", array($this->_table, $replycmd));
	}
	
	public function fetch_all_list($field='*',$where='') {
        $whereStr = '';
        if(!empty($where)){
            $whereStr = ' WHERE '.$where;
        }
		return DB::fetch_all("SELECT $field FROM %t $whereStr ORDER BY id DESC", array($this->_table));
	}
	
	public function delete_by_id($id) {
		return DB::query("DELETE FROM %t WHERE id=%d", array($this->_table, $id));
	}

}


?>
