<?php
header("Content-type:text/html;charset=utf-8");
define("TOKEN", "sucaihuo"); //自己定义的token 就是个通信的私钥
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();

//$wechatObj->responseMsg();
class wechatCallbackapiTest {

    public function valid() {
        $echoStr = $_GET["echostr"];
        if ($echoStr) {
            if ($this->checkSignature()) { //验证通过
                file_put_contents('access_token.txt', "jj." . date("Y-m-d H:i:s"));
                echo $echoStr;
            } else {
                file_put_contents('access_token.txt', "dd." . date("Y-m-d H:i:s"));
            }
        } else {
            $this->responseMsg();
            exit;
        }
        // file_put_contents('signature.txt',$this->checkSignature());
    }

    public function responseMsg() {

        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)) {
            include_once 'config.php';
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $scene_id = str_replace("qrscene_", "", $postObj->EventKey);

            $openid = $postObj->FromUserName; //openid
            $ToUserName = $postObj->ToUserName;  //转换角色
////            $toUsername = $postObj->ToUserName;
////            $keyword = trim($postObj->Content);
//        
            $Event = strtolower($postObj->Event);
            if ($Event == 'subscribe') {//首次关注
//                $content = "欢迎关注";
//                $str = $this->sendtext($openid, $ToUserName, $content);
//                echo $str;
                $is_first = 0;
                //插入表
            } elseif ($Event == 'scan') {//已关注
                $is_first = 1;
            }
            // file_put_contents('aa.txt', 'openid: ' . $openid."|scene_id:".$scene_id);
            $access_token = $this->getAccessToken();
            // file_put_contents('access_token.txt', date("Y-m-d H:i:s") . ': ' . $access_token . "***openid:" . $openid);
            $userinfo = $this->getUserinfo($openid, $access_token);
            $sql = "UPDATE `qrcode` SET `openid` = '" . $openid . "',logintime='" . time() . "',is_first=" . $is_first . ",nickname='" . $userinfo['nickname'] . "'"
                    . ",avatar='" . $userinfo['headimgurl'] . "',sex='" . $userinfo['sex'] . "',province='" . $userinfo['province'] . "',city='" . $userinfo['city'] . "',country='" . $userinfo['country'] . "' WHERE `id` =" . $scene_id . "";
// file_put_contents('aa.txt', 'postObj: ' . $sql);
            mysql_query($sql);
            file_put_contents('userinfo.txt', 'userinfo: ' . json_encode($userinfo) . $sql . "|" . $postObj->EventKey);
        } else {
            echo '咋不说哈呢';
            exit;
        }
    }

    private function getUserinfo($openid, $access_token) {
        if ($access_token && $openid) {
//            $url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
            $userinfo = $this->get_curl($url);
            return $userinfo;
        } else {
            return array("code" => "userinfo_null");
        }
    }

//https://www.zhihu.com/question/30074728
    public function getAccessToken() {
        $appid = 'wx422126b0b6bbfcfc';
        $secret = '45843e705995a12106155f4c26f716dc';

        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret . "";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $access_tokens = json_decode($result, true);
//file_put_contents('aa.txt', 'access_tokens: '.json_encode($access_tokens));
        $access_token = $access_tokens['access_token'];
        return $access_token;
    }

    public function get_curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $rs = $result ? json_decode($result, true) : "";
        return $rs;
    }

    private function checkSignature() {
        $echoStr = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 发送文本消息方法
     */

    private function sendtext($touser, $fromuser, $content) {
        $str = "<xml>
			  <ToUserName><![CDATA[" . $touser . "]]></ToUserName>
			  <FromUserName><![CDATA[" . $fromuser . "]]></FromUserName>
			  <CreateTime>" . time() . "</CreateTime>
			  <MsgType><![CDATA[text]]></MsgType>
			  <Content><![CDATA[" . $content . "]]></Content>
			  </xml>";
        return $str;
    }

    /*
     * 生成菜单
     */

    public function menu() {
        $data = ' {
		 "button":[
		 {
			   "name":"官网",
			   "sub_button":[
			   {	
				   "type":"view",
				   "name":"官网首页",
				   "url":"http://www.sucaihuo.com/"
				},
				{
				   "type":"view",
				   "name":"网页特效",
				   "url":"http://www.sucaihuo.com/js"
				},
				{
				   "type":"view",
				   "name":"网页模板",
				   "url":"http://www.sucaihuo.com/templates"
				},
				{
				   "type":"view",
				   "name":"php/mysql",
				   "url":"http://www.sucaihuo.com/php"
				}]
		   },
		  {
			   "name":"原创精品",
			   "sub_button":[
			   {	
				   "type":"view",
				   "name":"整站源码",
				   "url":"http://www.sucaihuo.com/source"
				},
				{
				   "type":"view",
				   "name":"精选网址",
				   "url":"http://www.sucaihuo.com/site"
				}]
		   }]
	 }';
        $token = $this->at();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$token";
        $res = $this->https_post($url, $data);

        return $res;
    }

}
?>


