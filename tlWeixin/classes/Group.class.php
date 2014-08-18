<?php
class Group {
	
	private $accessToken;
	private $uri="https://api.weixin.qq.com/cgi-bin/groups/";
	public $groups;
	
	function __construct($accessToken,$uri=NULL) {
		$this->accessToken=$accessToken;
		if ($uri) {
			$this->uri=$uri;
		}
	}
	
	public function postRequest($url,$data) {
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
// 		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
// 		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;;
	}
	
	/**
	 * 获取当前分组的数据数组<br />
	 * @return array
	 * 格式：<br />
	 * array(
	 * 		array("id"=>0,"name"=>"未分组","count"=>2),
	 * 		array("id"=>1,"name"=>"黑名单","count"=>0),
	 * 		array("id"=>2,"name"=>"星标组","count"=>0)
	 * )
	 */
	public function getExistGroups(){
		$url = $this->uri."get?access_token=".$this->accessToken;
		$groups = file_get_contents($url);
		$groupsArray = json_decode($groups,true);
		return $groupsArray['groups'];
	}
	
	public function addOneGroup($groupName) {
		$url=$this->uri."create?access_token=".$this->accessToken;
		$data = '{"group":{"name":"'.$groupName.'"}}';
		return $this->postRequest($url, $data);
	}
	
	public function getGroupIdByOpenId($openId){
		$url=$this->uri."getid?access_token=".$this->accessToken;
		$data = '{"openid":"'.$openId.'"}';
		$groupIdArray=json_decode($this->postRequest($url, $data),true);
		return $groupIdArray['groupid'];
	}
	
	/**
	 * 修改用户分组的名称
	 * @param int $existGroupId 这个id不能是系统的id，当前系统的id是0，1，2，用户id从100开始，否则会返回errorcode：-1
	 * @param string $groupName
	 */
	public function modifyGroupName($existGroupId,$groupName) {
		$url = $this->uri."update?access_token=".$this->accessToken;
		$data = '{"group":{"id":'.$existGroupId.',"name":"'.$groupName.'"}}';
		return $this->postRequest($url, $data);
	}
	
	public function moveUserToGroup($openId,$existGroupId) {
		$url = $this->uri."members/update?access_token=".$this->accessToken;
		$data ='{"openid":"'.$openId.'","to_groupid":'.$existGroupId.'}';
		return $this->postRequest($url, $data);
	}
	
// 	public function deleteGroup($existGroupId) {
// 		$url=$this->uri."delete?access_token=".$this->accessToken;
// 		$data = '{"groupid":'.$existGroupId.'}';
// 		return $this->postRequest($url, $data);
// 	}

}

?>