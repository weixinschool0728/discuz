<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$replyInfo = C::t('#tom_weixin#tom_weixin_reply')->fetch_by_replycmd($hookKeyword);
if(is_array($replyInfo) && !empty($replyInfo)){
    if($replyInfo['reply_type'] == 1){
        $replyInfo['reply_text'] = str_replace("{n}", "\n", $replyInfo['reply_text']);
        $outArr = array(
            'type'      => 'text',
            'content'   => $replyInfo['reply_text'],
        );
        $isDoHookContent = true;
        $exitHookScript = true;
    }else if($replyInfo['reply_type'] == 2){
        $outArr = array(
            'type'      => 'news',
            'content'   => '',
        );
        $replyExtParam = explode("||||", $replyInfo['reply_text']);
        $replyExtParam['1'] = str_replace("{n}", "\n", $replyExtParam['1']);
        $newsItem = array(
            'title' => $replyExtParam['0'],
            'description' => $replyExtParam['1'],
            'picUrl' => $replyExtParam['2'],
            'url' => $replyExtParam['3'],
        );
        $outArr['content'][] = $newsItem;
        $isDoHookContent = true;
        $exitHookScript = true;
    }else if($replyInfo['reply_type'] == 3){
        $outArr = array(
            'type'      => 'music',
            'content'   => '',
        );
        $replyExtParam = explode("||||", $replyInfo['reply_text']);
        $replyExtParam['1'] = str_replace("{n}", "\n", $replyExtParam['1']);
        $musicItem = array(
            'title' => $replyExtParam['0'],
            'description' => $replyExtParam['1'],
            'musicUrl' => $replyExtParam['2'],
            'hQMusicUrl' => $replyExtParam['2'],
        );
        $outArr['content'] = $musicItem;
        $isDoHookContent = true;
        $exitHookScript = true;
    }
}
?>
