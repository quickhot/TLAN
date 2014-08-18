<?php
class Response {
	
	public $newsContent = array();
	
	public function textMsg($toUser,$fromUser,$content,$msgId=NULL) {
		$now=time();
		$textXml="<xml><ToUserName><![CDATA[$toUser]]></ToUserName><FromUserName><![CDATA[$fromUser]]></FromUserName><CreateTime>$now</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[$content]]></Content><MsgId>$msgId</MsgId></xml>";
		return $textXml;
	}
	
	public function picMsg($toUser,$fromUser,$mediaId,$msgId=NULL) {
		$now=time();
		$picXml="<xml><ToUserName><![CDATA[$toUser]]></ToUserName><FromUserName><![CDATA[$fromUser]]></FromUserName><CreateTime>$now</CreateTime><MsgType><![CDATA[image]]></MsgType><Image><MediaId><![CDATA[$mediaId]]></MediaId></Image><MsgId>$msgId</MsgId></xml>";
		return $picXml;
	}
	
	public function voiceMsg($toUser,$fromUser,$mediaId){
		$now=time();
		$voiceXml="<xml><ToUserName><![CDATA[$toUser]]></ToUserName><FromUserName><![CDATA[$fromUser]]></FromUserName><CreateTime>$now</CreateTime><MsgType><![CDATA[voice]]></MsgType><Voice><MediaId><![CDATA[$mediaId]]></MediaId></Voice></xml>";
		return $voiceXml;
	}
	
	public function videoMsg($toUser,$fromUser,$mediaId,$thumbMediaId,$title=NULL,$desc=NULL) {
		$now=time();
		$videoXml="<xml><ToUserName><![CDATA[$toUser]]></ToUserName><FromUserName><![CDATA[$fromUser]]></FromUserName><CreateTime>$now</CreateTime><MsgType><![CDATA[video]]></MsgType><Video><MediaId><![CDATA[$mediaId]]></MediaId><ThumbMediaId><![CDATA[$thumbMediaId]]></ThumbMediaId><Title><![CDATA[$title]]></Title><Description><![CDATA[$desc]]></Description></Video></xml>";
		return $videoXml;
	}
	
	public function musicMsg($toUser,$fromUser,$title=NULL,$desc=NULL,$musicUrl=NULL,$HQMusicUrl=NULL,$thumbMediaId) {
		$now=time();
		$musicXml = "<xml><ToUserName><![CDATA[$toUser]]></ToUserName><FromUserName><![CDATA[$fromUser]]></FromUserName><CreateTime>$now</CreateTime><MsgType><![CDATA[music]]></MsgType><Music><Title><![CDATA[$title]]></Title><Description><![CDATA[$desc]]></Description><MusicUrl><![CDATA[$musicUrl]]></MusicUrl><HQMusicUrl><![CDATA[$HQMusicUrl]]></HQMusicUrl><ThumbMediaId><![CDATA[$thumbMediaId]]></ThumbMediaId></Music></xml>";
		return $musicXml;
	}
	
	/**
	 * 
	 * @param string $toUser
	 * @param string $fromUser
	 * @param array $newsContent
	 */
	public function newsMsg($toUser,$fromUser){
		$now=time();
		$count = count($this->newsContent);
		$articles = "";
		foreach ($this->newsContent as $oneNews) {
			$articles.="<item>
<Title><![CDATA[".$oneNews['Title']."]]></Title> 
<Description><![CDATA[".$oneNews['Description']."]]></Description>
<PicUrl><![CDATA[".$oneNews['PicUrl']."]]></PicUrl>
<Url><![CDATA[".$oneNews['Url']."]]></Url>
</item>";
		}
		$newsMsg="<xml>
<ToUserName><![CDATA[$toUser]]></ToUserName>
<FromUserName><![CDATA[$fromUser]]></FromUserName>
<CreateTime>$now</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>$count</ArticleCount>
<Articles>$articles</Articles></xml>";
		return $newsMsg;
	}
	
	/**
	 * 把整理好的新闻数组$newsContent填入进来
	 * @param array $newsContent
	 * 数组格式为
	 * array{
	 * [0]=>array("Title"=>"title1","Description"=>"desc1","PicUrl"=>"picUrl1","Url"=>url1),
	 * [1]=>array("Title"=>"title2","Description"=>"desc2","PicUrl"=>"picUrl2","Url"=>url2),
	 * ...
	 * }
	 */
	public function setArticles($newsContent){
		$this->newsContent = $newsContent;
	}
	
	/**
	 * 添加一条新闻
	 * @param unknown $title
	 * @param unknown $desc
	 * @param unknown $picUrl
	 * @param unknown $url
	 */
	public function addOneNews($title,$desc,$picUrl,$url) {
		$this->newsContent[]=array("Title"=>$title,"Description"=>$desc,"PicUrl"=>$picUrl,"Url"=>$url);
	}
	 
	public function sendXml($xml) {
		echo $xml;
	}
}
?>