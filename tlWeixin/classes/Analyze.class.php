<?php
include_once 'XmlToArray.class.php';
class Analyze extends XmlToArray{
	
	function __construct() {
	}

	public function convertXml($postStr,$isJson=FALSE){
		$xmlToArray = $this->setXml($postStr);
		//获取完整的Array内容
		$xmlArray = $this->parseXml();
		//根据微信的特点，实际上没有attributes，那么就去除掉，包括他的头<xml>
		$xmlArray = $xmlArray['xml'];
		$this->removeAttribs($xmlArray);
		//把content内容直接提上来
		$this->getContent($xmlArray);
		unset($xmlArray["content"]);
		//var_dump($xmlArray);
		return $xmlArray;
	}
	
	/**
	 * 去除attribs
	 * @param unknown $array
	 */
	public function removeAttribs(&$array) {
 		foreach ($array as $key => $value) {
 			if ($key=='attributes') {
 				unset($array["$key"]);
 			} else {
 				$this->removeAttribs($array["$key"]);
 			}
 		}
	}
	
	/**
	 * 把content提上来，并去除第一级的content
	 * @param unknown $array
	 */
	public function getContent(&$array) {
		foreach ($array as $key=>$value) {
			$array["$key"]=$array["$key"]["content"];
		}
	}
	
}

?>