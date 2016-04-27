<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$outArr = array(
            'type'      => 'text',
            'content'   => '',
        );

$moduleActivity = $activityClass->getActivityData($tomActivity);
if(!$tomActivityStatus || empty($moduleActivity)){
    $moduleActivity['step'] = 'insearch';
    $activityClass->add($moduleActivity);
    $outArr['content'] = $moduleLang['search_msg'].$exitMsg;
}else{
    if($moduleActivity['step'] == 'insearch'){
        $fidArr = 'all';
        if(!empty($moduleSetting['fid']) && $moduleSetting['fid'] != '0'){
            $fidArr = explode(',',$moduleSetting['fid']);
        }
        $bookNum = (int)$moduleSetting['num'];
        if($bookNum > 10 || $bookNum < 1){
            $bookNum = 5;
        }
        $whereArr = array();
        $whereArr['sticky'] = 0;
        $whereArr['keywords'] = $keyword;
        $whereArr['inforum'] = $fidArr;
        if(isset($moduleSetting['is_image']) && $moduleSetting['is_image'] == 1){
            $whereArr['attach'] = 1;
        }
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
            $outArr['content'] = str_replace("{keyword}", $keyword, $moduleLang['nobook_msg']);
        }
        $activityClass->delete();
        $moduleActivity = array();
    }
}


?>