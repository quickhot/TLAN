<?php

class Media {
	private $url;
	private $accessToken;

	function __construct($accessToken,$url="http://file.api.weixin.qq.com/cgi-bin/media/") {
		$this->accessToken=$accessToken;
		$this->url=$url;
	}
	/**
	 * @param string $fileName 必须是服务器上的文件，本地文件不可以，需先上传到服务器上<br />
	 * @param unknown $type <br />
	 * 上传的多媒体文件有格式和大小限制，如下：<br />
	 * 图片（image）: 128K，支持JPG格式<br />
	 * 语音（voice）：256K，播放长度不超过60s，支持AMR\MP3格式<br />
	 * 视频（video）：1MB，支持MP4格式<br />
	 * 缩略图（thumb）：64KB，支持JPG格式<br />
	 * 媒体文件在后台保存时间为3天，即3天后media_id失效。<br />
	 * @return json 数据包<br />
	 * {
	 * 	"type":"image", 媒体类型<br />
	 * 	"media_id":"FZvnNzz--z1slVOXJZTZqE8emm26W4uSu73_odONqRIetL_AgyxrdJvIaNSVIQ3z",<br />
	 * "created_at":1395656595 创建时间<br />
	 * }
	 */
	public function upLoadMedia($fileName,$type) {
		$url = $this->url."upload?access_token=$this->accessToken&type=$type";
		$data = array("media"=>"@".$fileName);
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
// 		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
// 		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
	
	/**
	 * 获取媒体文件信息
	 * @param string $mediaId
	 * @return array
	 * 样例：
	 *  Array<br />
(<br />
    [Server] => nginx/0.7.64<br />
    [Date] => Tue, 25 Mar 2014 04:10:31 GMT<br />
    [Content-Type] => image/jpeg<br />
    [Connection] => keep-alive<br />
    [Content-disposition] => attachment; filename="2_DvnvXcpA8FS42Xb9XO1nYR2K5t5HQAIxKTiqZoRAiIEaN_a7hTIWLSdPKci3Eu.jpg"<br />
    [Cache-Control] => no-cache, must-revalidate<br />
    [Content-Length] => 75597<br />
    [curlInfo] => Array<br />
        (<br />
            [url] => http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=MnAAzfEWRAb0QxJxN_FspcI12DQO5L7sQS36sSJXj0liT8XnrrSJ-bp98oz2mHC78Md-pYL4P9AnzjlS4SSdrXhdw-6-nFKkJo_MtDheDad8zrk79e80woM1VncuU9xW9qb3ExXQ4KA4FNCAAUfqGg&media_id=2_DvnvXcpA8FS42Xb9XO1nYR2K5t5HQAIxKTiqZoRAiIEaN_a7hTIWLSdPKci3Eu<br />
            [content_type] => image/jpeg<br />
            [http_code] => 200<br />
            [header_size] => 307<br />
            [request_size] => 317<br />
            [filetime] => -1<br />
            [ssl_verify_result] => 0<br />
            [redirect_count] => 0<br />
            [total_time] => 0.174129<br />
            [namelookup_time] => 0.006743<br />
            [connect_time] => 0.067552<br />
            [pretransfer_time] => 0.067557<br />
            [size_upload] => 0<br />
            [size_download] => 0<br />
            [speed_download] => 0<br />
            [speed_upload] => 0<br />
            [download_content_length] => 75597<br />
            [upload_content_length] => 0<br />
            [starttransfer_time] => 0.174089<br />
            [redirect_time] => 0<br />
            [certinfo] => Array<br />
                (<br />
                )<br />
            [redirect_url] =><br /> 
        )<br />
)<br />
	 */
	public function getMediaInfo($mediaId) {
		$url = $this->url."get?access_token=$this->accessToken&media_id=$mediaId";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 1); //只取头信息？1：是
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$header = curl_exec($ch);
		$headerArray = array();
		$var=preg_replace("/\r\n\r\n.*\$/",'',$header);
		$var=explode("\r\n",$var);
		foreach($var as $i){
			if(preg_match('/^([a-zA-Z -]+): +(.*)$/',$i,$parts))
				$headerArray[$parts[1]]=$parts[2];
			}
		$info = curl_getinfo($ch);
		curl_close($ch);
		$headerArray[curlInfo]=$info;
		return $headerArray;
	}
	
	public function getFileNameByHeader($headerArray){
		$contentDis = $headerArray['Content-disposition'];//获取包括文件名的Content-disposition
		$ContentDisArray = explode(';', $contentDis);//转成数组
		$oriFile = $ContentDisArray[1];//获取原始文件  filename="2_DvnvXcpA8FS42Xb9XO1nYR2K5t5HQAIxKTiqZoRAiIEaN_a7hTIWLSdPKci3Eu.jpg"
		$oriFileName = substr($oriFile, strpos($oriFile,'="')+2,-1);
		return $oriFileName;
	}
	
	/**
	 * 写媒体文件
	 * @param string $mediaId
	 * @param string $fileName 绝对路径的文件名，文件扩展名可以通过getFileNameByHeader来获得
	 * @return boolean
	 */
	public function downloadMedia($mediaId,$fileName) {
		$url = $this->url."get?access_token=$this->accessToken&media_id=$mediaId";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_NOBODY, 0); //只取头信息？0：不是
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$package = curl_exec($ch);
		curl_close($ch);
		$localFile = fopen($fileName,'w');
		if ($localFile!==false) {
			if (fwrite($localFile, $package)) {
				fclose($localFile);
				return true;
			} return false;
		} return false;
	}
	
}

?>