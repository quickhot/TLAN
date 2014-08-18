<?php
include_once '../conf/weixin.php';
class Users {
	private $appId = appID;
	private $secret = appsecret;
	private $accessToken = ACCESS_TOKEN;
	private $uri;
	private $userAccessToken = array();
	function __construct($accessToken=NULL,$uri="https://api.weixin.qq.com/cgi-bin/user/") {
		if ($accessToken) {
			$this->accessToken=$accessToken;
		}
		if ($uri) {
			$this->uri=$uri;
		}
	}
	
	/**
	 * 获取用户详细信息
	 * @param string $openId
	 * @param string $lang zh_CN 简体，zh_TW 繁体，en 英语
	 * @return array
	 * example:
	 * array(10) { <br />
	 * ["subscribe"]=> int(1)<br /> 
	 * ["openid"]=> string(28) "oMIYit4vcSrbGnpKGWJVD8QqvFlA"<br /> 
	 * ["nickname"]=> string(8) "quickhot" <br />
	 * ["sex"]=> int(1) ["language"]=> string(5) "zh_CN"<br /> 
	 * ["city"]=> string(6) "河北" <br />
	 * ["province"]=> string(6) "天津" <br />
	 * ["country"]=> string(6) "中国" <br />
	 * ["headimgurl"]=> string(127) "http://wx.qlogo.cn/mmopen/RnTXofyrN73I28AM3AbjhGK5A1NTQL7vdzwl5NhZ31gT6OKdXwojiadCrqqDmicPWNScEPtER58iaShnFhqO1HgxgBUcHnB6QCF/0"<br /> 
	 * ["subscribe_time"]=> int(1395993614) }<br />
	 * <B>参数	说明</B><br />
	 * subscribe	 用户是否订阅该公众号标识，值为0时，代表此用户没有关注该公众号，拉取不到其余信息。<br />
	 * openid	 用户的标识，对当前公众号唯一<br />
	 * nickname	 用户的昵称<br />
	 * sex	 用户的性别，值为1时是男性，值为2时是女性，值为0时是未知<br />
	 * city	 用户所在城市<br />
	 * country	 用户所在国家<br />
	 * province	 用户所在省份<br />
	 * language	 用户的语言，简体中文为zh_CN<br />
	 * headimgurl	 用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空<br />
	 * subscribe_time	 用户关注时间，为时间戳。如果用户曾多次关注，则取最后关注时间<br />
	 */
	public function getUserDetailByOpenId($openId,$lang="zh_CN"){
		$url=$this->uri."info?access_token=$this->accessToken&openid=$openId&lang=$lang";
		$resp = file_get_contents($url);
		$respArray = json_decode($resp,true);
		return $respArray;
	}
	
	/**
	 * 获取关注的openId,当公众号关注者数量超过10000时，可通过填写next_openid的值，从而多次拉取列表的方式来满足需求。具体而言，就是在调用接口时，将上一次调用得到的返回中的next_openid值，作为下一次调用中的next_openid值。
	 * @param string $nextOpenId
	 * @return array
	 *  array(4) { <br />
	 *  ["total"]=> int(2)<br /> 
	 *  ["count"]=> int(2) <br />
	 *  ["data"]=> array(1) { <br />
	 *  	["openid"]=> array(2) { <br />
	 *  		[0]=> string(28) "oMIYit4vcSrbGnpKGWJVD8QqvFlA"<br /> 
	 *  		[1]=> string(28) "oMIYity2u2hEmi-qYTVqp7n96RSI" <br />
	 *  		} <br />
	 *  	} <br />
	 *  ["next_openid"]=> string(28) "oMIYity2u2hEmi-qYTVqp7n96RSI"<br /> 
	 *  }<br />
	 */
	public function getOpenIds($nextOpenId=null) {
		$url=$this->uri."get?access_token=$this->accessToken&next_openid=$nextOpenId";
		$resp = file_get_contents($url);
		$respArray = json_decode($resp,true);
		return $respArray;
	}
	
	/**
	 * 用户授权的跳转<br />
	 * @param unknown $url
	 * @param unknown $scope
	 * @param string $state
	 * @return string
	 * 参数	是否必须	说明<br />
	 * url	 是	 授权后重定向的回调链接地址，请使用urlencode对链接进行处理<br />
	 * scope	 是	 应用授权作用域，snsapi_base （不弹出授权页面，直接跳转，只能获取用户openid），snsapi_userinfo （弹出授权页面，可通过openid拿到昵称、性别、所在地。并且，即使在未关注的情况下，只要用户授权，也能获取其信息）<br />
	 * state	 否	 重定向后会带上state参数，开发者可以填写a-zA-Z0-9的参数值<br />
	 */
	public function oauthRedirect($url,$scope,$state=null) {
		$url=urlencode($url);
		$openUrl="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$this->appId&redirect_uri=$url&response_type=code&scope=$scope&state=$state#wechat_redirect";
		return $openUrl;
	}
	
	/**
	 * 通过code获取用户的accessToken（此为该用户的accessToken，为了访问该用户的数据所使用，不是基础accessToken）
	 * @param unknown $code
	 * @return array
	 * array(<br />
   "access_token"=>"ACCESS_TOKEN",<br />
   "expires_in"=>7200,<br />
   "refresh_token"=>"REFRESH_TOKEN",<br />
   "openid"=>"OPENID",<br />
   "scope"=>"SCOPE")<br />
access_token	 网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同<br />
expires_in	 access_token接口调用凭证超时时间，单位（秒）<br />
refresh_token	 用户刷新access_token<br />
openid	 用户唯一标识<br />
scope	 用户授权的作用域，使用逗号（,）分隔<br />
	 */
	public function getUserAccessTokenByCode($code) {
		$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appId&secret=$this->secret&code=$code&grant_type=authorization_code";
		$resp = file_get_contents($url);
		$this->userAccessToken[$code]=json_decode($resp,true);
		$this->userAccessToken[$code]['createTime']=time();		
		return $this->userAccessToken[$code];
	}
	
	/**
	 * 通过userAccessToken获取用户的详细资料，前提是用户在跳转的时候的作用域，必须是snsapi_userinfo，否则会返回48001错误
	 * @param unknown $userAccessToken
	 * @param unknown $openId
	 * @param string $lang
	 * @return mixed
	 * array(9) {["openid"]=>string(28) "oMIYity2u2hEmi-qYTVqp7n96RSI"
  ["nickname"]=>string(3) "猫"
  ["sex"]=>int(1)
  ["language"]=>string(5) "zh_CN"
  ["city"]=>string(6) "南开"
  ["province"]=>string(6) "天津"
  ["country"]=>string(6) "中国"
  ["headimgurl"]=>string(131) "http://wx.qlogo.cn/mmopen/INk4JvWfe8VjX7En9GoWzG0ky2KLxnaAicPQR69f4zVaoiauM8h7iaN62gCxL7QvyicibeSFsyo1gVggEbmia0RQKkoAzxXctAf2Oia/0"
  ["privilege"]=>array(0) {}
}*/
	public function getUserDetailByUserAccessToken($userAccessToken,$openId,$lang="zh_CN") {
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=$userAccessToken&openid=$openId&lang=$lang";
		$resp = file_get_contents($url);
		return json_decode($resp,true);
	}
	
	/**
	 * 通过refresh_token获取用户的accessToken（此为该用户的accessToken，为了访问该用户的数据所使用，不是基础accessToken）
	 * 由于access_token拥有较短的有效期，当access_token超时后，可以使用refresh_token进行刷新，refresh_token拥有较长的有效期（7天、30天、60天、90天），当refresh_token失效的后，需要用户重新授权。
	 * @param unknown $refresh_token
	 * @return array
	 * array(<br />
	 *"access_token"=>"ACCESS_TOKEN",<br />
	 *"expires_in"=>7200,<br />
	 *"refresh_token"=>"REFRESH_TOKEN",<br />
	 *"openid"=>"OPENID",<br />
	 *"scope"=>"SCOPE")<br />
	 *
	 *access_token	 网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同<br />
	 *expires_in	 access_token接口调用凭证超时时间，单位（秒）<br />
	 *refresh_token	 用户刷新access_token<br />
	 *openid	 用户唯一标识<br />
	 *scope	 用户授权的作用域，使用逗号（,）分隔<br />
	 */
	public function refreshUserAccessToken($refresh_token) {
		$url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=$this->appId&grant_type=refresh_token&refresh_token=$refresh_token";
		$resp = file_get_contents($url);
		$this->userAccessToken[$code]=json_decode($resp,true);
		$this->userAccessToken[$code]['createTime']=time();
		return $this->userAccessToken[$code];
	}
	
}

?>