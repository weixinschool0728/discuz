<?php

/*
   This is NOT a freeware, use is subject to license terms
   ��Ȩ���У�TOM΢�� www.tomwx.net
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
} 

class table_tom_pintuan_order extends discuz_table{
	public function __construct() {
        parent::__construct();
		$this->_table = 'tom_pintuan_order';
		$this->_pk    = 'id';
	}

    public function fetch_by_id($id,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE id=%d", array($this->_table, $id));
	}
    
    public function fetch_by_order_no($order_no,$field='*') {
		return DB::fetch_first("SELECT $field FROM %t WHERE order_no=%s", array($this->_table, $order_no));
	}
	
    public function fetch_all_list($condition,$orders = '',$start = 0,$limit = 10) {
		$data = DB::fetch_all("SELECT * FROM %t WHERE 1 %i $orders LIMIT $start,$limit",array($this->_table,$condition));
		return $data;
	}
    
    public function fetch_all_like_list($condition,$orders = '',$start = 0,$limit = 10,$goods_name='') {
        if(!empty($goods_name)){
            $data = DB::fetch_all("SELECT * FROM %t WHERE 1 %i AND goods_name LIKE %s $orders LIMIT $start,$limit",array($this->_table,$condition,'%'.$goods_name.'%'));
        }else{
            $data = DB::fetch_all("SELECT * FROM %t WHERE 1 %i $orders LIMIT $start,$limit",array($this->_table,$condition));
        }
		
		return $data;
	}
    
    public function fetch_all_like_count($condition,$goods_name='') {
        if(!empty($goods_name)){
            $return = DB::fetch_first("SELECT count(*) AS num FROM %t WHERE 1 %i AND goods_name LIKE %s ",array($this->_table,$condition,'%'.$goods_name.'%'));
        }else{
            $return = DB::fetch_first("SELECT count(*) AS num FROM %t WHERE 1 %i ",array($this->_table,$condition));
        }
		return $return['num'];
	}
    
    public function fetch_all_sun_goods_num($condition) {
        $return = DB::fetch_first("SELECT SUM(goods_num) AS sun_goods_num FROM ".DB::table($this->_table)." WHERE 1 $condition ");
		return $return['sun_goods_num'];
	}
    
    public function fetch_all_sun_pay_price($condition) {
        $return = DB::fetch_first("SELECT SUM(pay_price) AS sun_pay_price FROM ".DB::table($this->_table)." WHERE 1 $condition ");
		return $return['sun_pay_price'];
	}
    
    public function update_tuan_status_by_tuan_id($tuan_id,$tuan_status) {
		return DB::query("UPDATE %t SET tuan_status=%d WHERE tuan_id=%d", array($this->_table, $tuan_status, $tuan_id));
	}
    
    public function insert_id() {
		return DB::insert_id();
	}
    
    public function fetch_all_count($condition) {
        $return = DB::fetch_first("SELECT count(*) AS num FROM ".DB::table($this->_table)." WHERE 1 $condition ");
		return $return['num'];
	}
	
	public function delete_by_id($id) {
		return DB::query("DELETE FROM %t WHERE id=%d", array($this->_table, $id));
	}

}


?>
