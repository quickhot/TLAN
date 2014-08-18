<?php
include_once 'General.class.php';
class ServiceMsg {
	private $accessToken;
	private $url = "https://api.weixin.qq.com/cgi-bin/message/custom/";
	public $newsContent = array();
	
	function __construct($accessToken,$url=NULL){
		$this->accessToken = $accessToken;
		if ($url) {
			$this->url = $url;
		}
	}
	
	private function sendMsg($jsonString) {
		$url = $this->url.'send?access_token='.$this->accessToken;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
// 		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
// 		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$jsonString);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
	
	public function textMsg($openId,$text) {
		$jsonString='{"touser":"'.$openId.'","msgtype":"text","text":{"content":"'.$text.'"}}';
		return $this->sendMsg($jsonString);
	}
	
	public function imgMsg($openId,$mediaId) {
		$jsonString='{"touser":"'.$openId.'","msgtype":"image","image":{"media_id":"'.$mediaId.'"}}';
		return $this->sendMsg($jsonString);
	}
	
	public function voiceMsg($openId,$mediaId) {
		$jsonString='{"touser":"'.$openId.'","msgtype":"voice","voice":{"media_id":"'.$mediaId.'"}}';
		return $this->sendMsg($jsonString);
	}
	
	public function videoMsg($openId,$mediaId,$thumbMediaId,$title=null,$desc=null) {
		$jsonString = '{"touser":"'.$openId.'","msgtype":"video","video":{"media_id":"'.$mediaId.'","thumb_media_id":"'.$thumbMediaId.'","title":"'.$title.'","description":"'.$desc.'"}}';
		return $this->sendMsg($jsonString);
	}
	
	public function musicMsg($openId,$musicUrl,$hqMusicUrl,$thumbMediaId,$title=null,$desc=null) {
		$jsonString='{"touser":"'.$openId.'","msgtype":"music","music":{"title":"'.$title.'","description":"'.$desc.'","musicurl":"'.$musicUrl.'","hqmusicurl":"'.$hqMusicUrl.'","thumb_media_id":"'.$thumbMediaId.'"}}';
		return $this->sendMsg($jsonString);
	}
	
	public function newsMsg($openId) {
		$msgArray=array("touser"=>$openId,"msgtype"=>"news");
		$msgArray['news']['articles'] = $this->newsContent;
		$general = new General();
		$msgArray = $general->multiDimensionalArrayMap('urlencode',$msgArray);
		$jsonString = urldecode(json_encode($msgArray));
		return $this->sendMsg($jsonString);
	}
	
	public function addOneNews($title,$desc,$url,$picUrl){
		$this->newsContent[]=array('title'=>$title,'description'=>$desc,'url'=>$url,"picurl"=>$picUrl);
	}
	
}

?>