<?php
class General {
	
	public function wlencode($data) {
		$base64 = base64_encode($data);
		if (substr($base64, -2) == '==') {
			$base = substr($base64, 0,-2);
		} else $base = $base64;
		$baseFront = substr($base, 0,6);
		$baseBehind = substr($base, 6);
		return $baseBehind.$baseFront;
	}
	
	public function wldecode($data) {
		$front = substr($data, -6);
		$behind = substr($data, 0,-6);
		return base64_decode($front.$behind.'==');
	}
	
	/**
	 * 获取文件扩展名
	 * @param string $fileName
	 * @return string
	 */
	public function getFileExtension($fileName){
		$extend=explode('.',$fileName);
		$va=count($extend)-1;
		return $extend[$va];
	}
	
	/**
	 * 递归的array_map
	 * @param funciton $func
	 * @param array $arr
	 * @return array
	 */
	public function multiDimensionalArrayMap($func,$arr)
	{
		$newArr=array();
		foreach($arr as $key=>$value)
		{
			$newArr[$key]=(is_array($value)?$this->multiDimensionalArrayMap($func,$value):$func($value));
		}
		return $newArr;
	}
	
	public function sendSMS($phoneNo,$content){
		$cpid    = '3312';//--------------------------->>企业ID，请联系我们索取免费测试帐号
		$cppwd   = strtoupper(MD5("772330"));//---------->>ID密码
		$httpstr = "http://223.4.131.214:8080/mt/?cpid={$cpid}&cppwd={$cppwd}&phone={$phoneNo}&encode=utf8&msgtext=".urlencode($content);
		$result  = @file_get_contents($httpstr);
		if($result == '0'){
			return 1;
		}else{
			return -18;
		}
	}
	
}

?>