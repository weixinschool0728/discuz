<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class weixinCallbackApi
{
    var $msgObj          = NULL;
    var $toUserName     = "";
    var $fromUserName  = "";
    var $openId          = "";
    var $tom_weixin     = NULL;
    
    public function __construct() {
        $this->tom_weixin = tom_weixin::getInstance();
    }
    
    public function check() {
        $echoStr = $_GET["echostr"];
        if ($this->checkSignature()) {
            echo htmlspecialchars($echoStr);
        }
        exit;
    }
    
    public function runCheck(){
        if ($this->checkSignature()) {
            $this->run();
        }else{
            exit;
        }
    }

    public function run()
    {
        header("Content-Type: text/xml; charset=utf-8");
        $result = $this->responseMsg();
        echo $result;
        exit;
    }

    public function responseMsg()
    {
        $result = '';
        $postStr = file_get_contents("php://input");
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            if(is_object($postObj)){
                $this->msgObj           = $postObj;
                $this->toUserName      = $postObj->ToUserName;
                $this->fromUserName   = $postObj->FromUserName;
                $this->openId           = $postObj->FromUserName;
                $this->tom_weixin->set_openid($this->openId);
                $msgType = trim($postObj->MsgType);
                switch ($msgType){
                    case "event":
                        $this->tom_weixin->set_msgtype($msgType.'_'.$postObj->Event);
                        $result = $this->receiveEvent($postObj);
                        break;
                    case "text":
                        $this->tom_weixin->set_msgtype($msgType);
                        $result = $this->receiveText($postObj);
                        break;
                    case "image":
                        $this->tom_weixin->set_msgtype($msgType);
                        $result = $this->receiveImage($postObj);
                        break;
                    case "location":
                        $this->tom_weixin->set_msgtype($msgType);
                        $result = $this->receiveLocation($postObj);
                        break;
                    case "voice":
                        $this->tom_weixin->set_msgtype($msgType);
                        $result = $this->receiveVoice($postObj);
                        break;
                    case "video":
                        $this->tom_weixin->set_msgtype($msgType);
                        $result = $this->receiveVideo($postObj);
                        break;
                    case "link":
                        $this->tom_weixin->set_msgtype($msgType);
                        $result = $this->receiveLink($postObj);
                        break;
                    default:
                        $result = "unknow msg type: ".$msgType;
                        break;
                }
            }
        }
        return $result;
    }

    private function receiveEvent()
    {
        $result = "";
        switch ($this->msgObj->Event)
        {
            case "subscribe":
                $data = $this->tom_weixin->subscribe();
                $keyword = '';
                if(!empty($this->msgObj->EventKey)){
                    $keyword = str_replace("qrscene_","",$this->msgObj->EventKey);
                    $keyword = $this->in_iconv($keyword);
                    $data = $this->tom_weixin->scan($keyword);
                }
                $this->tom_weixin->msg($keyword);
                $result = $this->createMsg($data);
                break;
            case "unsubscribe":
                $data = $this->tom_weixin->unsubscribe();
                break;
            case "SCAN":
                $keyword = $this->in_iconv($this->msgObj->EventKey);
                $data = $this->tom_weixin->scan($keyword);
                $this->tom_weixin->msg($keyword);
                $result = $this->createMsg($data);
                break;
            case "CLICK":
                $keyword = $this->in_iconv($this->msgObj->EventKey);
                $data = $this->tom_weixin->click($keyword);
                $this->tom_weixin->msg($keyword);
                $result = $this->createMsg($data);
                break;
            case "LOCATION":
                $keyword = $this->msgObj->Latitude.'|||||'.$this->msgObj->Longitude.'|||||'.$this->msgObj->Precision;
                $keyword = $this->in_iconv($keyword);
                $data = $this->tom_weixin->location($keyword);
                $this->tom_weixin->msg($keyword);
                $result = $this->createMsg($data);
                break;
            case "VIEW":
                $keyword = $this->in_iconv($this->msgObj->EventKey);
                $data = $this->tom_weixin->view($keyword);
                $this->tom_weixin->msg($keyword);
                $result = $this->createMsg($data);
                break;
            default:
                break;
        }
        return $result;
    }

    private function receiveText()
    {
        $result = '';
        $keyword = $this->msgObj->Content;
        $keyword = $this->in_iconv($keyword);
        $data = $this->tom_weixin->text($keyword);
        $this->tom_weixin->msg($keyword);
        $result = $this->createMsg($data);
        
        return $result;
    }
    private function receiveImage(){
        $result = '';
        $keyword = $this->msgObj->PicUrl.'|||||'.$this->msgObj->MediaId;
        $keyword = $this->in_iconv($keyword);
        $data = $this->tom_weixin->text($keyword);
        $this->tom_weixin->msg($keyword);
        $result = $this->createMsg($data);
        return $result;
    }
    private function receiveLocation(){
        $result = '';
        $keyword = $this->msgObj->Location_X.'|||||'.$this->msgObj->Location_Y.'|||||'.$this->msgObj->Scale.'|||||'.$this->msgObj->Label;
        $keyword = $this->in_iconv($keyword);
        $data = $this->tom_weixin->text($keyword);
        $this->tom_weixin->msg($keyword);
        $result = $this->createMsg($data);
        return $result;
    }
    private function receiveVoice(){
        $result = '';
        $keyword = $this->msgObj->MediaId.'|||||'.$this->msgObj->Format;
        $keyword = $this->in_iconv($keyword);
        $data = $this->tom_weixin->text($keyword);
        $this->tom_weixin->msg($keyword);
        $result = $this->createMsg($data);
        return $result;
    }
    private function receiveVideo(){
        $result = '';
        $keyword = $this->msgObj->MediaId.'|||||'.$this->msgObj->ThumbMediaId;
        $keyword = $this->in_iconv($keyword);
        $data = $this->tom_weixin->text($keyword);
        $this->tom_weixin->msg($keyword);
        $result = $this->createMsg($data);
        return $result;
    }
    private function receiveLink(){
        $result = '';
        $keyword = $this->msgObj->Title.'|||||'.$this->msgObj->Description.'|||||'.$this->msgObj->Url;
        $keyword = $this->in_iconv($keyword);
        $data = $this->tom_weixin->text($keyword);
        $this->tom_weixin->msg($keyword);
        $result = $this->createMsg($data);
        return $result;
    }
    
    private function createMsg($dataArr = array()){
        $result = "";
        $dataInfo = array(
                'type'      => '',
                'content'   => '',
            );
        $dataInfo = array_merge($dataInfo,$dataArr);
        
        switch ($dataInfo['type']){
            case 'text':
                $result = $this->createText($dataInfo['content']);
                break;
            case 'news':
                $result = $this->createNews($dataInfo['content']);
                break;
            case 'image':
                $result = $this->createImage($dataInfo['content']);
                break;
            case 'music':
                $result = $this->createMusic($dataInfo['content']);
                break;
            default :
                break;
        }
        return $result;
    }
    private function createText($content = "")
    {
        $content = $this->out_iconv($content);
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                    </xml>";
        $result = sprintf($textTpl,  $this->fromUserName,$this->toUserName, time(), $content);
        return $result;
    }
    
    private function createNews($newsArr = array())
    {
        $result = "";
        if(is_array($newsArr) && !empty($newsArr)){
            $itemTpl = "<item>
                            <Title><![CDATA[%s]]></Title>
                            <Description><![CDATA[%s]]></Description>
                            <PicUrl><![CDATA[%s]]></PicUrl>
                            <Url><![CDATA[%s]]></Url>
                        </item>";
            $item_str = "";
            $i = 1;
            $newsCount = count($newsArr)<10? count($newsArr):10;
            foreach ($newsArr as $item){
                if($i <= 10){
                    $item['title']       = $this->out_iconv($item['title']);
                    $item['description'] = $this->out_iconv($item['description']);
                    $item['picUrl']      = $this->out_iconv($item['picUrl']);
                    $item['url']         = $this->out_iconv($item['url']);
                    $item_str .= sprintf($itemTpl, $item['title'], $item['description'], $item['picUrl'], $item['url']);
                }
                $i++;
            }
            $newsTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[news]]></MsgType>
                            <ArticleCount>%s</ArticleCount>
                            <Articles>$item_str</Articles>
                        </xml>";
            $result = sprintf($newsTpl,  $this->fromUserName,$this->toUserName, time(), $newsCount);
        }
        return $result;
    }
    
    private function createImage($imageArr = array())
    {
        $itemTpl = "<Image>
                        <MediaId><![CDATA[%s]]></MediaId>
                    </Image>";
        $item_str = sprintf($itemTpl, $imageArr['MediaId']);
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[image]]></MsgType>
                        $item_str
                    </xml>";
        $result = sprintf($textTpl,  $this->fromUserName,$this->toUserName, time());
        return $result;
    }
    
    private function createMusic($musicArr = array())
    {
        $musicArr['title']       = $this->out_iconv($musicArr['title']);
        $musicArr['description'] = $this->out_iconv($musicArr['description']);
        $musicArr['musicUrl']    = $this->out_iconv($musicArr['musicUrl']);
        $musicArr['hQMusicUrl']  = $this->out_iconv($musicArr['hQMusicUrl']);
        
        $itemTpl = "<Music>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        <MusicUrl><![CDATA[%s]]></MusicUrl>
                        <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                    </Music>";
        $item_str = sprintf($itemTpl, $musicArr['title'], $musicArr['description'], $musicArr['musicUrl'], $musicArr['hQMusicUrl']);
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[music]]></MsgType>
                        $item_str
                    </xml>";
        $result = sprintf($textTpl,  $this->fromUserName,$this->toUserName, time());
        return $result;
    }
    
    private function in_iconv($str){
        if (CHARSET != 'utf-8') {
            $str = diconv($str,'utf-8');
        }
        return $str;
    }
    
    private function out_iconv($str){
        if (CHARSET != 'utf-8' && !TOM_ICOVN) {
            $str = diconv($str,CHARSET,'utf-8');
        }
        return $str;
    }
    
    private function checkSignature()
    {
        if(TOM_TOKEN_CHECK){
            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];
            $token = TOM_TOKEN;
            $tmpArr = array($token, $timestamp, $nonce);
            sort($tmpArr, SORT_STRING);
            $tmpStr = implode($tmpArr);
            $tmpStr = sha1($tmpStr);

            if($tmpStr == $signature){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

}



?>
