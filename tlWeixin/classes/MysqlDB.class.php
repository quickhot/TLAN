<?php
class MysqlDB{
	public $link;
	
	function __construct($host,$dbUser,$dbPass,$dbName){
		$link = mysql_connect($host,$dbUser,$dbPass);
		if (!$link) {
			return -1;
		}
		mysql_query ( 'SET NAMES utf8' );
		mysql_select_db($dbName,$link);
		$this->link = $link;
	}
	
	function checkPhoneNo($phoneNo,$idCard) {
		$qryPhoneNo = "SELECT count(*) FROM staff WHERE mobileNo='$phoneNo' AND idCard='$idCard'";
		$resPhoneNo = mysql_query($qryPhoneNo,$this->link);
		$rowPhoneNo = mysql_fetch_row($resPhoneNo);
		$phoneNoCount = $rowPhoneNo[0];
		if ($phoneNoCount==1) {
			return true;
		} else return -5;
	}
	
	function requestVerifyCode($openId) {
		$newCode = $this->genCode();
		$qryVerifyCode = "SELECT count(*) FROM verify WHERE openId='$openId'";
		$resVerifyCode = mysql_query($qryVerifyCode,$this->link);
		$rowVerifyCode = mysql_fetch_row($resVerifyCode);
		$rowCount = $rowVerifyCode[0];
		if ($rowCount==0) {
			$qryNewCode = "INSERT INTO verify VALUES('$openId','$newCode')";
		} else {
			$qryNewCode = "UPDATE verify SET verifyCode='$newCode' WHERE openId='$openId'";
		}
		$res = mysql_query($qryNewCode,$this->link);
		if ($res) {
			return $newCode;
		} else return -2;
	}	
	
	function addNewUser($openId) {
		$qryNewUser="INSERT IGNORE INTO verify(openId) VALUES('$openId')";
		$res = mysql_query($qryNewUser,$this->link);
		if ($res) {
			return 1;
		} else {
			return -4;
		}
	}
	
	function genCode(){
		return mt_rand(100000, 999999);
	}
	
	function doRegist($verifyCode,$phoneNo,$idCard,$openId) {
		$qryVerifyCode = "SELECT verifyCode FROM verify WHERE openId='$openId'";
		$resVerifyCode = mysql_query($qryVerifyCode,$this->link);
		$rowVerifycode = mysql_fetch_row($resVerifyCode);
		$dbVerifyCode = $rowVerifycode[0];
		if ($dbVerifyCode==$verifyCode){
			$updStaff = "UPDATE staff SET openId='$openId' WHERE mobileNo='$phoneNo' AND idCard='$idCard'";
			$resStaff = mysql_query($updStaff,$this->link);
			if ($resStaff) {
				$updatedRows = mysql_affected_rows($this->link);
				if ($updatedRows>0) {
					return 1;
				} else {
					return -9;
				}
			} else {
				return -8;
			}
		} else {
			return -7;
		}
	}
	
	function getUserDetail($openId) {
		$qryUserDetail = "SELECT * FROM v_userdetail WHERE openId='$openId'";
		$resUserDetail = mysql_query($qryUserDetail,$this->link);
		if ($resUserDetail) {
			$rowUserDetal = mysql_fetch_assoc($resUserDetail);
			return $rowUserDetal;
		} else {
			return -10;
		}
	}
	
	function checkRegist($openId) {
		$qryRegist = "SELECT id FROM staff WHERE openId='$openId'";
		$resRegist = mysql_query($qryRegist,$this->link);
		$rowRegist = mysql_fetch_row($resRegist);
		$staffId = $rowRegist[0];
		if ($staffId) {
			return $staffId;
		} else return -11;
	}
	
	function getBrands(){
		$qryBrands = "SELECT * FROM brand";
		$resBrands = mysql_query($qryBrands,$this->link);
		if($resBrands) {
			$retArray = array();
			while($rowBrands=mysql_fetch_array($resBrands,MYSQL_NUM)){
				$retArray[] = $rowBrands;
			}
			return $retArray;
		} else {
			return -12;
		}
		
	}
	
}