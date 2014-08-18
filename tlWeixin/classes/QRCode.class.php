<?php
class QRCode {
	private $accessToken;
	function __construct($accessToken){
		$this->accessToken=$accessToken;
	}
	/**
	 * 
	 * @param int $scene 场景id，用户自己随便写，扫二维码的时候会获得
	 * @param int $expireSec ，失效时间，最小60秒，小于60秒就是60秒。不填写，就是永久有效
	 * @return array
	 *("ticket"=>"gQF77zoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL3hFd1VDa3ptcUtYUkR1T2F1V0wwAAIElX86UwMEPAAAAA==","expire_seconds"=>60)
	 *永久有效的，没有expire_seconds
	 */
	public function createQRCode($scene,$expireSec=NULL) {
		$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$this->accessToken";
		$dataArray=array();
		$dataArray['action_info']=array('scene'=>array('scene_id'=>$scene));
		if ($expireSec) {
			$dataArray['expire_seconds']=$expireSec;
			$dataArray['action_name']='QR_SCENE';
		} else {
			$dataArray['action_name']='QR_LIMIT_SCENE';
		}
		$data = json_encode($dataArray);
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$response = curl_exec($ch);
		curl_close($ch);
		return json_decode($response,true);
	}
	/**
	 * 获取ticket对应的二维码图片，可以用<img src=""/>显示
	 * @param string $ticket
	 * @return string
	 */
	public function getQRImgUrlByTicket($ticket){
		$urlTicket = urldecode($ticket);
		return "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$urlTicket";
	}
}

?>