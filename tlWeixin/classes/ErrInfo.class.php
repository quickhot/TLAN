<?php
class ErrInfo {
	public $errCode = array(
		"1"=>"操作成功完成",
		"-1"=>"数据库连接失败",
		"-2"=>"获取验证码失败",
		"-3"=>"不存在的openId",
		"-4"=>"插入新openId失败",
		"-5"=>"手机号和身份证号与预留的不匹配",
		"-6"=>"生成验证码失败",
		"-7"=>"输入的验证码不正确",
		"-8"=>"更新员工openId失败",
		"-9"=>"更新员工openId失败，请勿重复注册",
		"-10"=>"获取员工详细数据失败",
		"-11"=>"员工未注册，请先进行员工注册"
	);
	
	public function getErrInfoByCode($code) {
		return $this->errCode["$code"];
	}
}

?>