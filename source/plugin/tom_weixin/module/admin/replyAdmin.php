<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$replyBaseUrl = $moduleBaseUrl.'&act=admin&moduleid=reply';
$replyListUrl = 'action=plugins&operation=config&do='.$pluginid.'&identifier=tom_weixin&pmod=module&act=admin&moduleid=reply';
if($_GET['mact'] == 'add'){
    if(submitcheck('submit')){
        $replycmd = isset($_GET['replycmd'])? addslashes($_GET['replycmd']):'';
        $replydesc = isset($_GET['replydesc'])? addslashes($_GET['replydesc']):'';
        $reply_text = '';
        if($_GET['replytype'] == 1){
            $wbtxt = isset($_GET['wbtxt'])? trim($_GET['wbtxt']):'';
            $reply_text = $wbtxt;
        }else if($_GET['replytype'] == 2){
            $twtitle = isset($_GET['twtitle'])? addslashes($_GET['twtitle']):'-';
            $twtxt = isset($_GET['twtxt'])? addslashes($_GET['twtxt']):'-';
            $twsrc = isset($_GET['twsrc'])? addslashes($_GET['twsrc']):'-';
            $twurl = isset($_GET['twurl'])? addslashes($_GET['twurl']):'-';
            $reply_text = $twtitle.'||||'.$twtxt.'||||'.$twsrc.'||||'.$twurl;
        }else if($_GET['replytype'] == 3){
            $musictitle = isset($_GET['musictitle'])? addslashes($_GET['musictitle']):'-';
            $musictxt = isset($_GET['musictxt'])? addslashes($_GET['musictxt']):'-';
            $musicurl = isset($_GET['musicurl'])? addslashes($_GET['musicurl']):'-';
            $reply_text = $musictitle.'||||'.$musictxt.'||||'.$musicurl;
        }
        $insertData = array();
        $insertData['reply_cmd']  = $replycmd;
        $insertData['reply_type'] = $_GET['replytype'];
        $insertData['reply_desc'] = $replydesc;
        $insertData['reply_text'] = $reply_text;
        C::t('#tom_weixin#tom_weixin_reply')->insert($insertData);
        cpmsg($tomScriptLang['act_success'], $replyListUrl, 'succeed');
    }else{
        showformheader('plugins&operation=config&do=' . $pluginid . '&identifier=tom_weixin&pmod=module&act=admin&moduleid=reply&mact=add&replytype='.$_GET['replytype']);
        showtableheader();
        echo '<tr><th colspan="15" class="partition"><a href="'.$replyBaseUrl.'"><font color="#F60"><b>' . $moduleLang['reply_list_back'] . '</b></font></a></th></tr>';
        echo '<tr class="header"><th>'.$moduleLang['reply_cmd'].'</th><th></th></tr>';
        echo '<tr><td width="300"><input name="replycmd" type="text" value="" size="40" /></td><td>'.$sysCmdString.$tomScriptLang['not_cmd_msg'].'</td></tr>';
        echo '<tr class="header"><th>'.$moduleLang['reply_desc'].'</th><th></th></tr>';
        echo '<tr><td><input name="replydesc" type="text" value="" size="40"/></td><td></td></tr>';
        
        if($_GET['replytype'] == 1){
            echo '<tr class="header"><th>'.$moduleLang['wb_txt'].'</th><th></th></tr>';
            echo '<tr><td><textarea rows="6" name="wbtxt" cols="40" class="tarea"></textarea></td><td>'.$moduleLang['txt_msg'].'</td></tr>';
        }else if($_GET['replytype'] == 2){
            echo '<tr class="header"><th>'.$moduleLang['tw_title'].'</th><th></th></tr>';
            echo '<tr><td><input name="twtitle" type="text" value="" size="40"/></td><td></td></tr>';
            echo '<tr class="header"><th>'.$moduleLang['tw_txt'].'</th><th></th></tr>';
            echo '<tr><td><textarea rows="6" name="twtxt" cols="40" class="tarea"></textarea></td><td>'.$moduleLang['txt_msg'].'</td></tr>';
            echo '<tr class="header"><th>'.$moduleLang['tw_src'].'</th><th></th></tr>';
            echo '<tr><td><input name="twsrc" type="text" value="" size="60"/></td><td></td></tr>';
            echo '<tr class="header"><th>'.$moduleLang['tw_url'].'</th><th></th></tr>';
            echo '<tr><td><input name="twurl" type="text" value="" size="60"/></td><td></td></tr>';
        }else if($_GET['replytype'] == 3){
            echo '<tr class="header"><th>'.$moduleLang['music_title'].'</th><th></th></tr>';
            echo '<tr><td><input name="musictitle" type="text" value="" size="40"/></td><td></td></tr>';
            echo '<tr class="header"><th>'.$moduleLang['music_txt'].'</th><th></th></tr>';
            echo '<tr><td><textarea rows="6" name="musictxt" cols="40" class="tarea"></textarea></td><td>'.$moduleLang['txt_msg'].'</td></tr>';
            echo '<tr class="header"><th>'.$moduleLang['music_url'].'</th><th></th></tr>';
            echo '<tr><td><input name="musicurl" type="text" value="" size="60"/></td><td>'.$moduleLang['music_url_msg'].'</td></tr>';
        }
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['mact'] == 'edit'){
    $replyInfo = C::t('#tom_weixin#tom_weixin_reply')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $replycmd = isset($_GET['replycmd'])? addslashes($_GET['replycmd']):'';
        $replydesc = isset($_GET['replydesc'])? addslashes($_GET['replydesc']):'';
        $reply_text = '';
        if($replyInfo['reply_type'] == 1){
            $wbtxt = isset($_GET['wbtxt'])? trim($_GET['wbtxt']):'';
            $reply_text = $wbtxt;
        }else if($replyInfo['reply_type'] == 2){
            $twtitle = isset($_GET['twtitle'])? addslashes($_GET['twtitle']):'-';
            $twtxt = isset($_GET['twtxt'])? addslashes($_GET['twtxt']):'-';
            $twsrc = isset($_GET['twsrc'])? addslashes($_GET['twsrc']):'-';
            $twurl = isset($_GET['twurl'])? addslashes($_GET['twurl']):'-';
            $reply_text = $twtitle.'||||'.$twtxt.'||||'.$twsrc.'||||'.$twurl;
        }else if($replyInfo['reply_type'] == 3){
            $musictitle = isset($_GET['musictitle'])? addslashes($_GET['musictitle']):'-';
            $musictxt = isset($_GET['musictxt'])? addslashes($_GET['musictxt']):'-';
            $musicurl = isset($_GET['musicurl'])? addslashes($_GET['musicurl']):'-';
            $reply_text = $musictitle.'||||'.$musictxt.'||||'.$musicurl;
        }
        $updateData = array();
        $updateData['reply_cmd']  = $replycmd;
        $updateData['reply_desc'] = $replydesc;
        $updateData['reply_text'] = $reply_text;
        C::t('#tom_weixin#tom_weixin_reply')->update($replyInfo['id'],$updateData);
        cpmsg($tomScriptLang['act_success'], $replyListUrl, 'succeed');
    }else{
        showformheader('plugins&operation=config&do=' . $pluginid . '&identifier=tom_weixin&pmod=module&act=admin&moduleid=reply&mact=edit&id='.$_GET['id']);
        showtableheader();
        echo '<tr><th colspan="15" class="partition"><a href="'.$replyBaseUrl.'"><font color="#F60"><b>' . $moduleLang['reply_list_back'] . '</b></font></a></th></tr>';
        echo '<tr class="header"><th>'.$moduleLang['reply_cmd'].'</th><th></th></tr>';
        echo '<tr><td width="300"><input name="replycmd" type="text" value="'.$replyInfo['reply_cmd'].'" size="40" /></td><td>'.$sysCmdString.$tomScriptLang['not_cmd_msg'].'</td></tr>';
        echo '<tr class="header"><th>'.$moduleLang['reply_desc'].'</th><th></th></tr>';
        echo '<tr><td><input name="replydesc" type="text" value="'.$replyInfo['reply_desc'].'" size="40"/></td><td></td></tr>';
        
        $extParam = explode("||||", $replyInfo['reply_text']);
        if($replyInfo['reply_type'] == 1){
            echo '<tr class="header"><th>'.$moduleLang['wb_txt'].'</th><th></th></tr>';
            echo '<tr><td><textarea rows="6" name="wbtxt" cols="40" class="tarea">'.$extParam['0'].'</textarea></td><td>'.$moduleLang['txt_msg'].'</td></tr>';
        }else if($replyInfo['reply_type'] == 2){
            echo '<tr class="header"><th>'.$moduleLang['tw_title'].'</th><th></th></tr>';
            echo '<tr><td><input name="twtitle" type="text" value="'.$extParam['0'].'" size="40"/></td><td></td></tr>';
            echo '<tr class="header"><th>'.$moduleLang['tw_txt'].'</th><th></th></tr>';
            echo '<tr><td><textarea rows="6" name="twtxt" cols="40" class="tarea">'.$extParam['1'].'</textarea></td><td>'.$moduleLang['txt_msg'].'</td></tr>';
            echo '<tr class="header"><th>'.$moduleLang['tw_src'].'</th><th></th></tr>';
            echo '<tr><td><input name="twsrc" type="text" value="'.$extParam['2'].'" size="60"/></td><td></td></tr>';
            echo '<tr class="header"><th>'.$moduleLang['tw_url'].'</th><th></th></tr>';
            echo '<tr><td><input name="twurl" type="text" value="'.$extParam['3'].'" size="60"/></td><td></td></tr>';
        }else if($replyInfo['reply_type'] == 3){
            echo '<tr class="header"><th>'.$moduleLang['music_title'].'</th><th></th></tr>';
            echo '<tr><td><input name="musictitle" type="text" value="'.$extParam['0'].'" size="40"/></td><td></td></tr>';
            echo '<tr class="header"><th>'.$moduleLang['music_txt'].'</th><th></th></tr>';
            echo '<tr><td><textarea rows="6" name="musictxt" cols="40" class="tarea">'.$extParam['1'].'</textarea></td><td>'.$moduleLang['txt_msg'].'</td></tr>';
            echo '<tr class="header"><th>'.$moduleLang['music_url'].'</th><th></th></tr>';
            echo '<tr><td><input name="musicurl" type="text" value="'.$extParam['2'].'" size="60"/></td><td>'.$moduleLang['music_url_msg'].'</td></tr>';
        }
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['mact'] == 'del'){
    C::t('#tom_weixin#tom_weixin_reply')->delete_by_id($_GET['id']);
    cpmsg($tomScriptLang['act_success'], $replyListUrl, 'succeed');
}else{
    $replyList = C::t('#tom_weixin#tom_weixin_reply')->fetch_all_list();
    showtableheader();
    echo '<tr><th colspan="15" class="partition">' . $moduleLang['reply_help_title'] . '</th></tr>';
    echo '<tr><td colspan="15" class="tipsblock" s="1"><ul id="tipslis">';
    echo '<li>' . $moduleLang['reply_help_1'] . '</li>';
    echo '</ul></td></tr>';
    echo '<tr><th colspan="15" class="partition">' . $moduleLang['reply_list_title'] . '</th></tr>';
    echo '<tr><th colspan="15">';
    echo '&nbsp;&nbsp;<a class="addtr" href="'.$replyBaseUrl.'&mact=add&replytype=1">' . $moduleLang['add_wb'] . '</a>';
    echo '&nbsp;&nbsp;<a class="addtr" href="'.$replyBaseUrl.'&mact=add&replytype=2">' . $moduleLang['add_tw'] . '</a>';
    echo '&nbsp;&nbsp;<a class="addtr" href="'.$replyBaseUrl.'&mact=add&replytype=3">' . $moduleLang['add_music'] . '</a>';
    echo '</th></tr>';
    echo '<tr class="header">';
    echo '<th>' . $moduleLang['reply_cmd'] . '</th>';
    echo '<th>' . $moduleLang['reply_desc'] . '</th>';
    echo '<th>' . $moduleLang['reply_type'] . '</th>';
    echo '<th>' . $tomScriptLang['handle'] . '</th>';
    echo '</tr>';
    
    foreach ($replyList as $key => $value) {
        $type_name = '';
        if($value['reply_type']==1){
            $type_name = $moduleLang['wb'];
        }else if($value['reply_type']==2){
            $type_name = $moduleLang['tw'];
        }else if($value['reply_type']==3){
            $type_name = $moduleLang['music'];
        }
        echo '<tr>';
        echo '<td>' . $value['reply_cmd'] . '</td>';
        echo '<td>' . $value['reply_desc'] . '</td>';
        echo '<td>' . $type_name . '</td>';
        echo '<td>';
        echo '<a href="'.$replyBaseUrl.'&mact=edit&id='.$value['id'].'&formhash='.FORMHASH.'">' . $moduleLang['reply_edit']. '</a>&nbsp;|&nbsp;';
        echo '<a href="'.$replyBaseUrl.'&mact=del&id='.$value['id'].'&formhash='.FORMHASH.'">' . $tomScriptLang['delete'] . '</a>';
        echo '</td>';
        echo '</tr>';
    }
    showtablefooter();
}
?>
