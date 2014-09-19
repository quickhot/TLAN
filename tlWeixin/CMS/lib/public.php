<?php

/**
 * Random generate string with letter and number in user wish length.
 *
 * @param int $length
 * string length
 *
 * @param boolean $max
 * if true generate the string's length equal "length", else string's length less or equal "length"
 *
 * @param contentType
 * 0:only generate numbers
 * 1:only generate numbers and lowcase letters
 * 2:generate numbers and allcase letters
 * @example echo random_string(12,true,2);
 */
function random_string($length, $max=FALSE, $contentType=2)
{
	if (is_int($max) && $max > $length)
	{
		$length = mt_rand($length, $max);
	}
	$output = '';
	 
	for ($i=0; $i<$length; $i++)
	{
		$which = mt_rand(0,$contentType); //随机哪些？
		 
		if ($which == 0) //随机数字
		{
			$output .= mt_rand(0,9);
		}
		elseif ($which == 1)//随机小写字母
		{
			$output .= chr(mt_rand(97,122));
		}
		elseif ($which ==2)
		{
			$output .= chr(mt_rand(65,90)); //随机大写字母
		}
	}
	return $output;
}


/**
 * Using this function may save upload img file to upload/ dir
 * and surfix with random characters.
 * input param is that HTML FORM POST para _FILE["file1"] by "<input type='file' name='file1'>"
 * return an array of
 * ["success"]:if true,upload successful,otherwise false
 * ["filename"]:if successful upload then the upload filename stored in. otherwise return a ZERO length string.
 * ["error"]:return error message,if success return a ZERO length string.
 * @param  $files param is POST from FORM _FILE["file"]</a><br>
 * @param $storePath param is path name of store the imgfile $storePath,like "advimg/"<br>
 * You should have the permission of write the file.<br>
 * 
 * @example
 * $getfiles=$_FILES["logo1"];<br>
 * $uploadResult	= storeimg($getfiles,'advImg/');<br>
 * if($uploadResult["success"]){<br>
 * $uploadedfile 	= $uploadResult["filename"];<br>
 * }else{<br>
 * echo $uploadResult["error"];<br>
 * }<br>
 *
 */
function storeimg($files,$storePath)
{
	$result = array("success"=>false,"filename"=>"","error"=>"");
	if (($files["type"] == "image/gif")
			|| ($files["type"] == "image/jpeg")
			|| ($files["type"] == "image/pjpeg")
			|| ($files["type"] == "image/png"))
	{
		if ($files["error"] > 0)
		{
			$result["error"] = "Return Code: " . $files["error"];
			return $result;
		}
		else
		{
			//echo "Upload: " . $files["name"] . "<br />";
			//echo "Type: " . $files["type"] . "<br />";
			//echo "Size: " . ($files["size"] / 1024) . " Kb<br />";
			//echo "Temp file: " . $files["tmp_name"] . "<br />";
			$tmpname = explode('php', $files["tmp_name"]);
			$outfile = $storePath.$tmpname[1]."_".$files["name"];
			if (file_exists($outfile))
			{
				$result["error"]	= $outfile . " already exists. Please just resubmit.";
				return $result;
			}
			else
			{
				//echo $outfile."<br>";
				if (move_uploaded_file($files["tmp_name"],$outfile)){
					//echo "Stored in: " . "upload/" . $files["name"];
					$result["filename"]=$outfile;
					$result["success"]=true;
					return $result;
				}else {
					$result["error"]= "Write to upload dir failed!";
					return $result;
				}
			}
		}
	}
	else
	{
		$result["error"]= "Invalid file";
		return $result;
	}
	 
}

/**
 * Get http server name without "http://"
 * @param
 * @example
 * echo getServerName();
 */
function getServerName(){
	$ServerName = strtolower($_SERVER['SERVER_NAME']?$_SERVER['SERVER_NAME']:$_SERVER['HTTP_HOST']);
	if(strpos($ServerName,'http://')){
		return str_replace('http://','',$ServerName);
	}
	return $ServerName;
}

/**
 * get script path of the running page
 * IN http://butcms.mobdev.local/notify/test.php
 * getScriptPath() will return
 * /notify
 */
function getScriptPath(){
	$scriptName = $_SERVER['SCRIPT_NAME'];
	$pos = strrpos($scriptName,'/');
	$path = substr($scriptName,0,$pos);
	return $path;
}

/**
 * change \ to \\
 * change ' to \'
 * change " to \"
 * Enter description here ...
 * @param unknown_type $oriString
 */
function chanString($oriString){
	$oriString=str_replace('\\', '\\\\', $oriString);
	$oriString=str_replace('\'', '\\\'', $oriString);
	$oriString=str_replace('\"', '\\\"', $oriString);

	return $oriString;
}


function htmlDispStr($oriString){
	$oriString=str_replace('\\', "&#092;", $oriString);
	$oriString=str_replace("'", "&#039;", $oriString);
	$oriString=str_replace('"', '&quot;', $oriString);
	$oriString=str_replace(' ', '&nbsp;', $oriString);
	$oriString=nl2br($oriString,false);

	return $oriString;
}

/**
 *
 * Send SMS message
 * @param string $phoneNo: The phone number your want to send.
 * @param string $message: The message you want to send. encode with utf-8.
 */
function sendSms($phoneNo,$message){
	if($phoneNo!=''&&$message!=''){
		$cpid    = '2583';//--------------------------->>企业ID，请联系我们索取免费测试帐号
		$cppwd   = strtoupper(MD5("187678"));//---------->>ID密码
		$httpstr = "http://58.53.128.167:8080/mt/?cpid={$cpid}&cppwd={$cppwd}&phone={$phoneNo}&msgtext=".urlencode($message)."&encode=utf8";
		$result  = @file_get_contents($httpstr);
		if($result == '0'){
			return TRUE;
		}else return FALSE;
	} else return FALSE;
}

function getSmsRemain(){
	$cpid	= '2583';
	$cppwd   = strtoupper(MD5("187678"));//---------->>ID密码
	$httpstr = "http://58.53.128.167:8080/qamount/?cpid={$cpid}&cppwd={$cppwd}";
	$result  = @file_get_contents($httpstr);
	return $result;
}

/**
 * return array with rooms recoder
 * Enter description here ...
 * @param unknown_type $userPhone
 * @param result_type int[optional] <p>
 * The type of array that is to be fetched. It's a constant and can
 * take the following values: MYSQL_ASSOC,
 * MYSQL_NUM, and
 * MYSQL_BOTH.
 * </p>
 * @return array an array of strings that corresponds to the fetched row, or false
 * if there are no more rows. The type of returned array depends on
 * how result_type is defined. By using
 * MYSQL_BOTH (default), you'll get an array with both
 * associative and number indices. Using MYSQL_ASSOC, you
 * only get associative indices (as mysql_fetch_assoc
 * works), using MYSQL_NUM, you only get number indices
 * (as mysql_fetch_row works).
 * </p>
 */
function getUserDetailByPhoneNo($userPhone,$result_type=MYSQL_ASSOC){

	include 'inc/conn.php';

	$conn=mysql_connect($dbhost,$dbuser,$dbpass);
	mysql_select_db($dbname, $conn);
	mysql_query("set names `utf8`");
	$query="SELECT * FROM userManage WHERE mobileNo='$userPhone'";
	$result=mysql_query($query);
	$rows=array();
	while($row=mysql_fetch_array($result,$result_type)){
		$rows[]=$row;
	}
	return $rows;
}

/**
 * return room detail by roomId
 * Enter description here ...
 * @param unknown_type $roomId
 */
function getRoomDetailByRoomId($roomId){
	include "inc/conn.php";
	$conn=mysql_connect($dbhost,$dbuser,$dbpass);
	mysql_select_db($dbname,$conn);
	mysql_query("set names `utf8`");
	$query="SELECT * FROM rooms WHERE id='$roomId'";
	$result=mysql_query($query);
	$rows=mysql_fetch_row($result);
	return $rows;
}

/**
 * return users from userManage table
 * Enter description here ...
 * @param unknown_type $roomId
 * @param int $result_type
 */
function getUsersByRoomId_urlen($roomId,$result_type){
	include 'inc/conn.php';
	$conn=mysql_connect($dbhost,$dbuser,$dbpass);
	mysql_select_db($dbname,$conn);
	mysql_query("set names `utf8`");
	$query="SELECT * FROM userManage WHERE roomId='$roomId'";
	$result=mysql_query($query);
	$rows=array();
	while ($row=mysql_fetch_array($result,$result_type)){
		$row=array_map('urlencode', $row);
		$rows[]=$row;
	}
	return $rows;
}
/**
 * return rooms detail by owner phone
 * Enter description here ...
 * @param unknown_type $ownerPhone
 * @param unknown_type $result_type
 */
function  getRoomDetailByOwnerPhone($ownerPhone,$result_type=MYSQL_BOTH){
	include 'inc/conn.php';
	$conn=mysql_connect($dbhost,$dbuser,$dbpass);
	mysql_select_db($dbname,$conn);
	mysql_query("set names `utf8`");
	$query="SELECT * FROM rooms WHERE mobileNo='$ownerPhone'";
	$result=mysql_query($query);
	$rows=array();
	while ($row=mysql_fetch_array($result,$result_type)){
		$rows[]=$row;
	}
	return $rows;
}

/**
 *
 * get roomIds by userPhone ...
 * @param unknown_type $userPhone
 */
function  getRoomIdByUserPhone($userPhone){
	include 'inc/conn.php';
	$conn=mysql_connect($dbhost,$dbuser,$dbpass);
	mysql_select_db($dbname,$conn);
	mysql_query("set names `utf8`");
	$query	="SELECT roomId FROM userManage WHERE mobileNo='$userPhone'";
	$result	=mysql_query($query);
	$rows	=array();
	while ($row=mysql_fetch_array($result)) {
		$rows[]=$row['roomId'];
	}
	return $rows;
}

/**
 * remove the '../' from URL string
 * remapURL('http://www.abc.com/abc/../def/../abc.html')
 * return http://www.abc.com/abc.html
 * 
 * @param string $URL
 */
function remapURL($URL){
	while($pos=strpos($URL,'/../')){
		$head=substr($URL,0,$pos);
		$posSlash = strrpos($head, '/');
		$realhead = substr($URL,0,$posSlash);
		$tail = substr($URL,$pos+4);
		$URL=$realhead.'/'.$tail;
	}
	return  $URL;
}

/**
 * 
 * Enter description here ...
 * @param unknown_type $phoneType
 * @param unknown_type $identifyNo
 * @param unknown_type $message
 * @param unknown_type $sound
 * @param unknown_type $badge
 * @param unknown_type $pemFile
 * @param unknown_type $deep 目录深度，根目录是0，依次类推
 */
function sendPushMessage($deep,$phoneType,$identifyNo,$message,$sound,$badge,$pemFile,$topic='ts'){
	$path='';
	for($i=0;$i<$deep;$i++){
		$path = '../'.$path;
	}
	//echo $path;
	if ($phoneType=='I') {
		include_once $path.'push/simplepushD.php';
		pushMessage($identifyNo, $message, $sound, $badge, $pemFile);
	}
	if ($phoneType=="A") {
		include_once $path.'PhpMQTT/SAM/php_sam.php';
		//create a new connection object
		$conn = new SAMConnection();
		//start initialise the connection
		$conn->connect(SAM_MQTT, array('SAM_HOST' => '192.168.2.250','SAM_PORT' => 1883));
		//create a new MQTT message with the output of the shell command as the body
		$msgCpu = new SAMMessage($message);
		//send the message on the topic cpu
		$conn->send('topic://'."$topic/".$identifyNo, $msgCpu);
		$conn->disconnect();
	}
}

?>