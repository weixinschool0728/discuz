<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class tom_module {
    
    var $moduleDir = '';
    
    public function __construct() {
        $this->moduleDir = DISCUZ_ROOT.'./source/plugin/tom_weixin/module/';
    }
    public function getOneByModuleId($moduleid = ''){
        return C::t('#tom_weixin#tom_weixin_module')->fetch_one_by_moduleid($moduleid);
    }
    public function getOneByModuleCmd($modulecmd = ''){
        return C::t('#tom_weixin#tom_weixin_module')->fetch_one_by_modulecmd($modulecmd);
    }
    public function insert($data = array()){
        return C::t('#tom_weixin#tom_weixin_module')->insert($data);
    }
    public function update($id,$data = array()){
        return C::t('#tom_weixin#tom_weixin_module')->update($id,$data);
    }
    public function deleteBymoduleId($moduleid = ''){
        return C::t('#tom_weixin#tom_weixin_module')->delete_by_moduleid($moduleid);
    }
    public function enableByModuleId($moduleid = ''){
        return C::t('#tom_weixin#tom_weixin_module')->enable_by_moduleid($moduleid);
    }
    public function disableByModuleId($moduleid = ''){
        return C::t('#tom_weixin#tom_weixin_module')->disable_by_moduleid($moduleid);
    }
    public function getInstallList($field='*',$where='',$order=''){
        return C::t('#tom_weixin#tom_weixin_module')->fetch_all_list($field,$where,$order);
    }

    public function encodeSetting($var = array(),$get = array()){
        $outStr = '';
        $settingArray = array();
        if(is_array($var) && !empty($var)){
            foreach ($var as $key => $value){
                $settingKey = $value['name'];
                if(isset($get[$settingKey])){
                    $settingValue = $get[$value['name']];
                }else{
                    $settingValue = $value['value'];
                }
                $settingValue = str_replace("\r\n","{rn}",$settingValue); 
                $settingValue = str_replace("\n","{n}",$settingValue); 
                $settingArray[$settingKey] = $settingValue;
            }
        }
        $outStr = base64_encode(serialize($settingArray));
        return $outStr;
    }
    
    public function decodeSetting($settingStr = ''){
        $outArr = array();
        if (!empty($settingStr)){
            $settingArrTmp = unserialize(base64_decode($settingStr));
            if(is_array($settingArrTmp) && !empty($settingArrTmp)){
                foreach ($settingArrTmp as $settingKey => $settingValue){
                    $settingValue = str_replace("{rn}","\r\n",$settingValue); 
                    $settingValue = str_replace("{n}","\n",$settingValue); 
                    $outArr[$settingKey] = $settingValue;
                }
            }
        }
        return $outArr;
    }

    public function getAdminName($moduleid = ''){
        $adminName = $this->moduleDir."admin/".$moduleid."Admin.php";
        if(file_exists($adminName)){
            return $adminName;
        }else{
            return false;
        }
    }
    public function getWeixinName($moduleid = ''){
        $weixinName = $this->moduleDir."weixin/".$moduleid.".php";
        if(file_exists($weixinName)){
            return $weixinName;
        }else{
            return false;
        }
    }
    
    public function getExtendName($moduleid = ''){
        $extendName = $this->moduleDir."extend/".$moduleid."Extend.php";
        if(file_exists($extendName)){
            return $extendName;
        }else{
            return false;
        }
    }

    public function getConfigName($moduleid = ''){
        $configName = '';
        if (CHARSET == 'gbk') {
            $configName = $this->moduleDir."config/gbk/".$moduleid."Config.php";
        }else{
            $configName = $this->moduleDir."config/utf8/".$moduleid."Config.php";
        }
        
        if(file_exists($configName)){
            return $configName;
        }else{
            return false;
        }
    }
    
    public function getAllList(){
        $configDir = '';
        if (CHARSET == 'gbk') {
            $configDir = $this->moduleDir."config/gbk/";
        }else{
            $configDir = $this->moduleDir."config/utf8/";
        }
        $moduleConfigArr = array();
        if(is_dir($configDir)){
            $moduleDirObj = dir($configDir);
            while(false !== ($configName = $moduleDirObj->read())){
                if(strpos($configName, "Config.php") !== false){
                    $moduleid = str_replace("Config.php", "", $configName);
                    $moduleid = trim($moduleid);
                    $moduleConfigArr[] = $moduleid;
                }
            }
            $moduleDirObj->close();
        }
        return $moduleConfigArr;
    }
    
    public function createExtendHtml($extendSettingArr = array()){
        $outStr = '';
        if(is_array($extendSettingArr) && !empty($extendSettingArr)){
            foreach ($extendSettingArr as $key => $value) {
                if($value['type'] == 'input'){
                   $outStr.= $this->createInput($value);
                }else if($value['type'] == 'textarea'){
                    $outStr.= $this->createTextarea($value);
                }else if($value['type'] == 'radio'){
                    $outStr.= $this->createRadio($value);
                }else if($value['type'] == 'select'){
                    $outStr.= $this->createSelect($value);
                }
            }
        }
        return $outStr;
    }
    
    public function createInput($config = array()){
        $outStr = '';
        $options = array(
            'title'  => 'title',
            'name'   => 'name',
            'value'  => '',
            'desc'   => '',
            'size'   => 40,
        );
        $options = array_merge($options, $config);
        
        $outStr = '<tr class="header"><th>'.$options['title'].'</th><th></th></tr>';
        $outStr.= '<tr><td><input name="'.$options['name'].'" type="text" value="'.$options['value'].'" size="'.$options['size'].'" /></td><td>'.$options['desc'].'</td></tr>';
        
        return $outStr;
    }
    
    public function createTextarea($config = array()){
        $outStr = '';
        $options = array(
            'title'  => 'title',
            'name'   => 'name',
            'value'  => '',
            'desc'   => '',
            'rows'   => 6,
            'cols'   => 40,
        );
        $options = array_merge($options, $config);
        
        $outStr = '<tr class="header"><th>'.$options['title'].'</th><th></th></tr>';
        $outStr.= '<tr><td><textarea rows="'.$options['rows'].'" name="'.$options['name'].'" cols="'.$options['cols'].'" >'.$options['value'].'</textarea></td><td>'.$options['desc'].'</td></tr>';
        
        return $outStr;
    }
    
    public function createRadio($config = array()){
        $outStr = '';
        $options = array(
            'title'  => 'title',
            'name'   => 'name',
            'value'  => '',
            'desc'   => '',
            'item'   => array(),
        );
        $options = array_merge($options, $config);
        
        $outStr = '<tr class="header"><th>'.$options['title'].'</th><th></th></tr>';
        
        $outStr.= "<tr><td>";
        
        foreach ($options['item'] as $key => $value){
            if($key == $options['value']){
                $outStr.= '<input type="radio" name="'.$options['name'].'" value="'.$key.'" checked>'.$value.'&nbsp;';
            }else{
                $outStr.= '<input type="radio" name="'.$options['name'].'" value="'.$key.'" >'.$value.'&nbsp;';
            }
            
        }
        
        $outStr.= '</td><td>'.$options['desc'].'</td></tr>';
        
        return $outStr;
    }
    
    public function createSelect($config = array()){
        $outStr = '';
        $options = array(
            'title'     => 'title',
            'name'      => 'name',
            'value'     => '',
            'desc'      => '',
            'item'      => array(),
        );
        $options = array_merge($options, $config);
        
        $outStr = '<tr class="header"><th>'.$options['title'].'</th><th></th></tr>';
        
        $outStr.= '<tr><td><select name="'.$options['name'].'" >';
        
        foreach ($options['item'] as $key => $value){
            if($key == $options['value']){
                $outStr.=  '<option value="'.$key.'" selected>'.$value.'</option>';
            }else{
                $outStr.=  '<option value="'.$key.'">'.$value.'</option>';
            }
            
        }
        $outStr.= '</select></td><td>'.$options['desc'].'</td></tr>';
        
        return $outStr;
    }
    
}

?>
