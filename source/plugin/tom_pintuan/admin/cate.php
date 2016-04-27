<?php

/*
   This is NOT a freeware, use is subject to license terms
   版权所有：TOM微信 www.tomwx.net
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$modBaseUrl = $adminBaseUrl.'&tmod=cate'; 
$modListUrl = $adminListUrl.'&tmod=cate';
$modFromUrl = $adminFromUrl.'&tmod=cate';

if($_GET['act'] == 'add'){
    if(submitcheck('submit')){
        $insertData = array();
        $insertData = __get_post_data();
        C::t('#tom_pintuan#tom_pintuan_cate')->insert($insertData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=add','enctype');
        showtableheader();
        __create_info_html();
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
    
}else if($_GET['act'] == 'edit'){
    $cateInfo = C::t('#tom_pintuan#tom_pintuan_cate')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($cateInfo);
        C::t('#tom_pintuan#tom_pintuan_cate')->update($cateInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        tomloadcalendarjs();
        loadeditorjs();
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($cateInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#tom_pintuan#tom_pintuan_cate')->delete_by_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');
}else{
    
    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $pagesize = 100;
    $start = ($page-1)*$pagesize;	
    $cateList = C::t('#tom_pintuan#tom_pintuan_cate')->fetch_all_list(""," ORDER BY csort ASC,id DESC ",$start,$pagesize);
    __create_nav_html();
    showtableheader();
    echo '<tr class="header">';
    echo '<th>' . $Lang['cate_name'] . '</th>';
    echo '<th>' . $Lang['cate_picurl'] . '</th>';
    echo '<th>' . $Lang['cate_csort'] . '</th>';
    echo '<th>' . $Lang['handle'] . '</th>';
    echo '</tr>';
    
    $i = 1;
    foreach ($cateList as $key => $value) {
        
        if(!preg_match('/^http/', $value['picurl']) ){
            $picurl = (preg_match('/^http:/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'tomwx/'.$value['picurl'];
        }else{
            $picurl = $value['picurl'];
        }
        
        echo '<tr>';
        echo '<td>' . $value['name'] . '</td>';
        echo '<td><img src="'.$picurl.'" width="40" /></td>';
        echo '<td>' . $value['csort'] . '</td>';
        echo '<td>';
        echo '<a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['cate_edit']. '</a>&nbsp;|&nbsp;';
        echo '<a href="javascript:void(0);" onclick="del_confirm(\''.$modBaseUrl.'&act=del&id='.$value['id'].'&formhash='.FORMHASH.'\');">' . $Lang['delete'] . '</a>';
        echo '</td>';
        echo '</tr>';
        $i++;
    }
    showtablefooter();
    $multi = multi($count, $pagesize, $page, $modBasePageUrl);	
    showsubmit('', '', '', '', $multi, false);
    
    $jsstr = <<<EOF
<script type="text/javascript">
function del_confirm(url){
  var r = confirm("{$Lang['makesure_del_msg']}")
  if (r == true){
    window.location = url;
  }else{
    return false;
  }
}
</script>
EOF;
    echo $jsstr;
    
}

function __get_post_data($infoArr = array()){
    $data = array();
    
    $name        = isset($_GET['name'])? addslashes($_GET['name']):'';
    $csort       = isset($_GET['csort'])? intval($_GET['csort']):10;
    
    $picurl = "";
    if($_GET['act'] == 'add'){
        $picurl        = tomuploadFile("picurl");
    }else if($_GET['act'] == 'edit'){
        $picurl        = tomuploadFile("picurl",$infoArr['picurl']);
    }

    $data['name']       = $name;
    $data['picurl']     = $picurl;
    $data['csort']      = $csort;
    
    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'name'              => '',
        'picurl'         => '',
        'csort'          => 10,
    );
    $options = array_merge($options, $infoArr);
    
    tomshowsetting(true,array('title'=>$Lang['cate_name'],'name'=>'name','value'=>$options['name'],'msg'=>$Lang['cate_name_msg']),"input");
    tomshowsetting(true,array('title'=>$Lang['cate_picurl'],'name'=>'picurl','value'=>$options['picurl'],'msg'=>$Lang['cate_picurl_msg']),"file");
    tomshowsetting(true,array('title'=>$Lang['cate_csort'],'name'=>'csort','value'=>$options['csort'],'msg'=>$Lang['cate_csort_msg']),"input");
    
    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    tomshownavheader();
    if($_GET['act'] == 'add'){
        tomshownavli($Lang['cate_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['cate_add'],"",true);
    }else if($_GET['act'] == 'edit'){
        tomshownavli($Lang['cate_list_title'],$modBaseUrl,false);
        tomshownavli($Lang['cate_add'],$modBaseUrl."&act=add",false);
        tomshownavli($Lang['cate_edit'],"",true);
    }else{
        tomshownavli($Lang['cate_list_title'],$modBaseUrl,true);
        tomshownavli($Lang['cate_add'],$modBaseUrl."&act=add",false);
    }
    tomshownavfooter();
}

?>
