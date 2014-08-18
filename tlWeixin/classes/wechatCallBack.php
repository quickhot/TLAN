<?php
include_once 'DAO.php';
class wechatCallBack {
	private $DAO;
	function __construct() {
		$this->DAO = new DAO ();
	}
	public function responseMsg() {
		$postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
		if (! empty ( $postStr )) {
			$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
			switch ($postObj->MsgType) {
				case 'text' :
					if ($postObj->Content == '1') {
						$xmlTpl = "<xml>
							<ToUserName><![CDATA[$postObj->FromUserName]]></ToUserName>
							<FromUserName><![CDATA[$postObj->ToUserName]]></FromUserName>
							<CreateTime>" . time () . "</CreateTime>
					        <MsgType><![CDATA[news]]></MsgType>
					        <ArticleCount>1</ArticleCount>
					        <Articles>
					        	<item>
					        		<Title><![CDATA[测试数据]]></Title>
					        		<Description><![CDATA[这个是一个测试页面。]]></Description>
					        		<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/GEJhIuGKPM0WibaFLbTsdibk7GRMH4ZdoXzOT2kRMEGClIE4uPxQa9qIRVaDwW1zzaiakMy8rmLeVBbvgXvhOibO5g/0]]></PicUrl>
					        		<Url><![CDATA[wx.quickhot.com/test3.html]]></Url>
						    	</item>
						    </Articles>
					</xml>";
						echo $xmlTpl;
					}
					break;
				
				case 'event' :
					if ($postObj->Event == 'subscribe') {
						// $this->DAO ->registUser($postObj->FromUserName);
						$eventKey = $this->DAO->getEventKey ( $postObj->ToUserName );
						if ($eventKey) {
							$xmlTpl = "<xml>
							<ToUserName><![CDATA[$postObj->FromUserName]]></ToUserName>
								<FromUserName><![CDATA[$postObj->ToUserName]]></FromUserName>
								<CreateTime>" . time () . "</CreateTime>
								<MsgType><![CDATA[news]]></MsgType>
								<ArticleCount>1</ArticleCount>
								<Articles>
								<item>
					            			<Title><![CDATA[颐贤里有礼 幸运刮刮起]]></Title>
					            			<Description><![CDATA[]]></Description>
					            			<PicUrl><![CDATA[http://wx.bigit.cn/yixianli/images/tutu.jpg]]></PicUrl>
					            			<Url><![CDATA[http://wx.bigit.cn/yixianli/guaguale.php?d=" . $postObj->FromUserName . "&k=$eventKey]]></Url>
					            			</item>
					            			</Articles>
					            			</xml>";
							echo $xmlTpl;
						}
					}
					break;
			}
		} else {
			echo "";
			exit ();
		}
	}
	public function valid() {
		$echoStr = $_GET ["echostr"];
		// valid signature , option
		if ($this->checkSignature ()) {
			echo $echoStr;
			exit ();
		}
	}
	private function checkSignature() {
		$signature = $_GET ["signature"];
		$timestamp = $_GET ["timestamp"];
		$nonce = $_GET ["nonce"];
		
		$token = TOKEN;
		$tmpArr = array (
				$token,
				$timestamp,
				$nonce 
		);
		sort ( $tmpArr );
		$tmpStr = implode ( $tmpArr );
		$tmpStr = sha1 ( $tmpStr );
		
		if ($tmpStr == $signature) {
			return true;
		} else {
			return false;
		}
	}
}

?>