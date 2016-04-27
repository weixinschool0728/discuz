<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$outArr = array(
            'type'      => 'text',
            'content'   => '',
        );
$bookNum = (int)$moduleSetting['num'];
if($bookNum > 10 || $bookNum < 1){
    $bookNum = 5;
}
$whereArr = array();
$whereArr['digest'] = 1;
$bookList = C::t('forum_thread')->fetch_all_search($whereArr,0, 0, $bookNum,'tid','DESC');
if(is_array($bookList) && !empty($bookList)){
    $i = 0;
    foreach ($bookList as $key => $value){
        $i++; 
        if($i == 1){
            $bookPicUrl = wx_forum_threadimage($value['tid'],'big');
        }else{
            $bookPicUrl = wx_forum_threadimage($value['tid']);
        }
        $bookUrl = wx_forum_login($openid, $_G['siteurl'].'forum.php?mod=viewthread&tid='.$value['tid']);
        $newsItem = array(
            'title' => $value['subject'],
            'description' => '',
            'picUrl' => $bookPicUrl,
            'url' => $bookUrl,
        );
        $outArr['content'][] = $newsItem;
    }
    $outArr['type'] = 'news';
}else{
    $outArr['content'] = $moduleLang['no_best_msg'];
}
?>