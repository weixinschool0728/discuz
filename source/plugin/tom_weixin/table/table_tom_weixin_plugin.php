<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
} 

class table_tom_weixin_plugin extends discuz_table{
	public function __construct() {
        parent::__construct();
		$this->_table = 'tom_weixin_plugin';
		$this->_pk    = 'id';
	}

    public function fetch_one_by_plugincmd($plugincmd,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE plugin_cmd=%s", array($this->_table, $plugincmd));
	}
    
    public function fetch_one_by_plugincmd2($plugincmd,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE plugin_cmd LIKE %s", array($this->_table, '%'.$plugincmd.'%'));
	}
	
	public function fetch_one_by_pluginid($pluginid,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE plugin_id=%s", array($this->_table, $pluginid));
	}
	
	public function fetch_all_list($field='*',$where='',$order='') {
        $orderStr = '';
        if(!empty($order)){
            $orderStr = ' ORDER BY '.$order;
        }
        $whereStr = '';
        if(!empty($where)){
            $whereStr = ' WHERE '.$where;
        }
		return DB::fetch_all("SELECT $field FROM %t $whereStr $orderStr", array($this->_table));
	}
	
	public function delete_by_pluginid($pluginid) {
		return DB::query("DELETE FROM %t WHERE plugin_id=%s", array($this->_table, $pluginid));
	}
    
    public function disable_by_pluginid($pluginid) {
		return DB::query("UPDATE %t SET status=2 WHERE plugin_id=%s", array($this->_table, $pluginid));
	}
    
    public function enable_by_pluginid($pluginid) {
		return DB::query("UPDATE %t SET status=1 WHERE plugin_id=%s", array($this->_table, $pluginid));
	}

}


?>
