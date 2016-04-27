<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(empty($outArr['type']) || empty($outArr['content'])){
    
    $searchInfo = $moduleClass->getOneByModuleId("search");
    $searchSetting = array();
    if(!empty($searchInfo['module_setting'])){
        $searchSetting = $moduleClass->decodeSetting($searchInfo['module_setting']);
    }
    
    if(!isset($searchSetting['is_hook'])){
        $searchSetting['is_hook'] = 1;
    }
    
    if(isset($searchSetting['is_hook']) && $searchSetting['is_hook'] == 1){
        $fidArr = 'all';
        if(!empty($searchSetting['fid']) && $searchSetting['fid'] != '0'){
            $fidArr = explode(',',$searchSetting['fid']);
        }
        $bookNum = (int)$searchSetting['num'];
        if($bookNum > 10 || $bookNum < 1){
            $bookNum = 5;
        }

        $whereArr = array();
        $whereArr['sticky'] = 0;
        $whereArr['keywords'] = $keyword;
        $whereArr['inforum'] = $fidArr;
        if(isset($searchSetting['is_image']) && $searchSetting['is_image'] == 1){
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
            $isDoHookContent = true;
            $exitHookScript = true;
        }
    }
    
    
}



?>
