<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_tom_portal_article_title extends discuz_table
{
	public function __construct() {

		$this->_table = 'portal_article_title';
		$this->_pk    = 'aid';

		parent::__construct();
	}

	public function fetch_all_by_title($subject,$catidArr = array(),$limit = 5) {
		$parameter = array($this->_table);
		$or = $wheresql = '';
        $catsql = ' AND 1=1 ';
		$subject = explode(',', str_replace(' ', '', $subject));
		if(empty($subject)) {
			return array();
		}
		for($i = 0; $i < count($subject); $i++) {
			if(preg_match("/\{(\d+)\}/", $subject[$i])) {
				$subject[$i] = preg_replace("/\\\{(\d+)\\\}/", ".{0,\\1}", preg_quote($subject[$i], '/'));
				$wheresql .= " $or title REGEXP %s";
				$parameter[] = $subject[$i];
			} else {
				$wheresql .= " $or title LIKE %s";
				$parameter[] = '%'.$subject[$i].'%';
			}
			$or = 'OR';
		}
        
        if(!empty($catidArr)){
            $catsql =" AND catid IN(".  implode(",", $catidArr).")";
        }
		return DB::fetch_all("SELECT * FROM %t WHERE status=0 $catsql AND ( $wheresql ) LIMIT {$limit} ", $parameter);
	}

}

?>