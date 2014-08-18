<?php
include_once 'General.class.php';
class Menu {
	private $accessToken;
	private $url;
	private $menu=array(array("type"=>"click","Empty","key"=>"1"),array("type"=>"click","name"=>"Empty","key"=>"1"),array("type"=>"click","name"=>"Empty","key"=>"1"));
	
	
	function __construct($accessToken,$url="https://api.weixin.qq.com/cgi-bin/menu/") {
		$this->accessToken=$accessToken;
		$this->url = $url;
	}
	
	/**
	 * 获取已经存在的菜单
	 * @return 菜单数组
	 */
	public function getExistMenu(){
		$menuJson = file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/get?access_token=$this->accessToken");
		$menuArray = json_decode(($menuJson),true);
		$menuArray = $menuArray['menu']['button'];
		$gen = new General();
		$menuArray = $gen->multiDimensionalArrayMap('urlencode', $menuArray);
		return $menuArray;
	}
	
	/**
	 * 设置菜单，格式可以通过getExistMenu来查看
	 * @param array $menu
	 */
	public function setMenu($menu) {
		if (count($menu)) {
			$this->menu = $menu;
		} else $this->menu = array(array("type"=>"click","Empty","key"=>"1"),array("type"=>"click","name"=>"Empty","key"=>"1"),array("type"=>"click","name"=>"Empty","key"=>"1"));
	}
	
	/**
	 * 获取当前设置的菜单数组
	 * @return array
	 */
	public function getMenu(){
		return $this->menu;
	}
	
	/**
	 * 添加一个菜单
	 * @param string $type click or view
	 * @param string $name 菜单名字
	 * @param string $value click 或 view的值 
	 * @param int $posMain 主菜单的位置 [1-3]
	 * @param int $posSec 子菜单位置 [1-5]
	 */
	public function addMenu($type,$name,$value,$posMain,$posSec) {
		$menu = $this->menu;
		if ($type=="click") {
			$valueType="key";
		} else if ($type="view") {
			$valueType="url";
		}
		
		$thisMenu = array(
				"type"=>$type,
				"name"=>urlencode($name),
				"$valueType"=>urlencode($value)
		);
		if ($posSec) {
			$subMenuCount = count($menu[$posMain-1]['sub_button']);
			if ($posSec>$subMenuCount) $posSec = $subMenuCount+1;
			$menu[$posMain-1]['sub_button'][intval($posSec-1)]=$thisMenu;
		} else {
			$menu[$posMain-1]=$thisMenu;
		}
		$this->menu = $menu;
	}
	
	/**
	 * 创建菜单
	 * @return string 返回创建的消息{"errcode":0,"errmsg":"ok"}表示成功
	 */
	public function createMenu() {
		$url = $this->url."create?access_token=$this->accessToken";
		//echo $url;
		$menu['button'] = $this->menu;
		$jsonMenu = urldecode(json_encode($menu));
		//echo $jsonMenu;
		//$data = array("body"=>$jsonMenu);
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
// 		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
// 		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$jsonMenu);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;;
	}

	public function deleteMenu() {
		$url = $this->url."delete?access_token=$this->accessToken";
		$response = file_get_contents($url);
		return $response;
	}
}

?>