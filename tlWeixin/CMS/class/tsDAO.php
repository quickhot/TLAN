<?php
/*
 * tsDAO 操作数据库方法
 * */
class  tsDAO{

    public $dbLink;

    function __construct($dbHost,$dbUser,$dbPass,$dbName) {
        $link = mysql_connect($dbHost,$dbUser,$dbPass);
        if ($link) {
            mysql_select_db($dbName);
            mysql_query("set names 'utf8'",$link);
            $this->dbLink = $link;
        } else return false;
    }
/**通过密码查询后台管理员表返回用户id和level
 * $username   用户名
 * $password    密码
**/
	public function getAdminIdAndLevel($username,$password ){
		$qryAdmin = "SELECT `id`,`level` FROM `admin` WHERE adminName='".$username."' AND adminPass=MD5('".$password."')";
		$resAdmin = mysql_query($qryAdmin,$this->dbLink);
		if ($resAdmin) {
		    $rowAdmin = mysql_fetch_row($resAdmin);
		    return $rowAdmin;
		} else return false;
	}
/**
 * 通过adminId获取菜单
 * @param unknown $adminId
 * @return multitype:multitype:
 */
	public function getAccess($adminId){
	    $sql = "SELECT `menuId` FROM `accessControl` WHERE `adminId`='$adminId'";
	    $res = mysql_query($sql,$this->dbLink);
	    $result = array();
	    while (($row = mysql_fetch_array($res,MYSQL_NUM))!=false) {
	        $result[]=$row;
	    }
	    return $result;
	}

	/**
	 *提取后台一级菜单
	 */
	public function getAmenu(){
	    $sql="select * from menu where parent_id=0";
	    $res = mysql_query($sql,$this->dbLink);
	    $result = array();
	    while (($row = mysql_fetch_array($res,MYSQL_ASSOC))!=false) {
	        $result[]=$row;
	    }
	    return $result;
	}
	 /**
	 * 提取菜单项目的子菜单记录集
	 * $parent_id  父级栏目id
	 */
	public function getSubmenu($parent_id){
		$sql="select * from menu where parent_id=".$parent_id;
		$res = mysql_query($sql,$this->dbLink);
		$result = array();
		while (($row = mysql_fetch_array($res,MYSQL_ASSOC))!=false) {
		    $result[]=$row;
		}
		return $result;
	}
	/**
	 * updateOper 修改数据
	 * $data_array  需要修改的表字段数据（是以数组的形式）
	 * $conditions_array  条件（是以一维数组的形式）
	 */
	public function updateOper($data_array,$conditions_array){
	    foreach ($data_array AS $field => $value){
	        $fieldValueStr .=  "`".$field."`='".$value."',";
	    }
	    $fieldValueStr = substr($fieldValueStr,0,-1);
	    foreach ($conditions_array AS $field => $value){
	        $conditionStr .= $field."='".$value."' AND ";
	    }
	    $conditionStr = substr($conditionStr,0,-5);
	    $update = "UPDATE admin SET ".$fieldValueStr." WHERE ".$conditionStr."";
	    if (mysql_query($update,$this->dbLink)) {
	        return true;
	    } else return false;
	}
	/**
	 * 根据admin表里的id获取管理员级别
	 * @param unknown $db
	 * @param unknown $ESId
	 * @param unknown $user_id
	 */
	public function getLevelById($user_id) {
	    $sql = " SELECT `level` FROM admin WHERE id=$user_id";
	    $res = mysql_query($sql,$this->dbLink);
		if ($res) {
		    $result = mysql_fetch_row($res);
		    return $result[0];
		} else return false;
	}

	/**
	 * deleteOper 删除数据
	 * $conditions_array  条件（是以一维数组的形式）
	 */
	public function deleteOper($userId){
	    $sql = "DELETE FROM admin WHERE id=$userId";
	    $res = mysql_query($sql,$this->dbLink);
		if ($res) {
		    return true;
		} else return false;
	}
	/**
	 *  通过$username获取管理员表数据
	 *  $username 用户名
	 */
	public function getAdministratorByUsername($username){
	    $sql="SELECT * FROM admin WHERE adminName='$username'";
	    $res = mysql_query($sql,$this->dbLink);
		$result = array();
		while (($row = mysql_fetch_array($res,MYSQL_ASSOC))!=false) {
		    $result[]=$row;
		}
		return $result;
	}
	/**
	 * insertOper 修改数据
	 * $table 表名
	 * $data_array  需要添加的表字段数据（是以数组的形式）
	 */
	public function insertOper($data_array){
        foreach ($data_array AS $field => $value){
            $fieldStr .= $field.",";
            $valueStr .= "'".$value."',";
        }
        $fieldStr = substr($fieldStr,0,-1);
        $valueStr = substr($valueStr,0,-1);
        $insert = "insert into admin (".$fieldStr.") values (".$valueStr.")";
        mysql_query($insert,$this->dbLink);
        return mysql_insert_id($this->dbLink);
	}

	/**
	 *  获取管理员表数据
	 *
	 */
	public function getAdministrator(){
	    $sql="SELECT * FROM admin ORDER BY `adminName`";
	    $res = mysql_query($sql,$this->dbLink);
	    $result = array();
	    while (($row = mysql_fetch_array($res,MYSQL_ASSOC))!=false) {
	        $result[]=$row;
	    }
	    return $result;
	}
	/** 获得permissionInfo总数
	 * @param unknown $adminId
	 */
	public function getPermissionInfoCount($adminId){
	    $sql = "SELECT COUNT(*) AS `count` FROM `v_permissioninfoview`
	    WHERE parent_id<>0 AND adminId=$adminId";
	    $res = mysql_query($sql,$this->dbLink);
		if ($res) {
		    $result = mysql_fetch_row($res);
		    return $result[0];
		} else return false;
	}

	/** 获得详细权限分配列表内容
	 * by Nico
	 * @param unknown $db
	 * @param unknown $adminId
	 * @param unknown $sidx
	 * @param unknown $sord
	 * @param unknown $start
	 * @param unknown $limit
	 */
	public function getPermissionInfo($adminId,$sidx,$sord,$start,$limit){
	    $sql = "SELECT v_listMenus.`id`,v_listMenus.`name`,v_listMenus.`parName`, accessControl.`adminId` FROM v_listMenus
	    LEFT JOIN accessControl ON accessControl.`menuId`=v_listMenus.`id` AND adminId=$adminId";
	    $res = mysql_query($sql,$this->dbLink);
	    $result = array();
	    while (($row = mysql_fetch_array($res,MYSQL_NUM))!=false) {
	        $result[]=$row;
	    }
	    return $result;
	}
	/** 权限分配操作
	 * @param 被分配权限的管理员ID $adminId
	 * @param 被分配的权限 $saveParam
	 */
	public function permissionSave($adminId,$saveParam){
	    $table = "accessControl";
//先删除之前的权限
	    $delqry = "DELETE FROM accessControl WHERE adminId=$adminId";
	    mysql_query($delqry,$this->dbLink);
	    $result = true;
	    for($i=0;$i<count($saveParam);$i++){
	        try{
	            $resultInsert[$i] = mysql_query("INSERT INTO accesscontrol(adminId,menuId) VALUES($adminId,".$saveParam[$i].")",$this->dbLink);
	        }catch(Exception $e){
	            $resultInsert[$i] = 0;
	        }
	        $result = $result&&$resultInsert[$i];
	    }
	    return $result;
	}
	/**
	 * 获取代理数量
	 * @return Ambigous <>|boolean
	 */
	public function getAgentCount(){
	    $sql="SELECT COUNT(*) FROM agent;";
	    $res = mysql_query($sql,$this->dbLink);
	    if ($res) {
	        $row=mysql_fetch_row($res);
	        return $row[0];
	    } else return false;
	}
	/**
	 *获取代理数据
	 */
	public function getAgent($sidx,$sord,$start,$limit){
	    $sql="SELECT * FROM agent";
	    if ($sidx) {
	        $sql=$sql." ORDER BY $sidx $sord";
	    }
	    if ($start!='') {
	        $sql=$sql." LIMIT $start,$limit";
	    }
	    $res = mysql_query($sql,$this->dbLink);
	    if ($res) {
	        $ret = array();
	        while (($row=mysql_fetch_array($res,MYSQL_NUM))!=false) {
	            $ret[] = $row;
	        }
	        return $ret;
	    } else return false;
	}
//TODO: MODIFY
	/**
	 * 获取注册用户数据
	 */
	public function getLogon($db,$ESId,$sidx,$sord,$start,$limit){
		$sql="SELECT `userInfo`.`id`,`mobileInfo`.`account`,`mobileInfo`.`mobileNo` FROM  `userInfo` LEFT JOIN `mobileInfo` ON `mobileInfo`.`id` = `userInfo`.`mobileId`  WHERE `userInfo`.`identity` = 4 AND `userInfo`.`attentionESId` = ".$ESId." ORDER BY $sidx $sord LIMIT $start,$limit";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_NUM);
	}
	/**
	 * 获取推荐政策数据
	 */
	public function getRecommendPolicy($db,$ESId,$sidx,$sord,$start,$limit){
		$sql="SELECT `id`,`title`,`content` FROM `recommendPolicy` WHERE `ESId` =".$ESId." ORDER BY $sidx $sord LIMIT $start,$limit";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_NUM);
	}

	/**
	 * 获取客户数据
	 */
	public function getUsers($db,$ESId,$sidx,$sord,$start,$limit){
		$sql="SELECT `id`,`alias`,`nickname`,`mobileNo`,`gender`,`roomName`,`signature`,`birthday`,
		`IDCardNo`,`address`,`tele`,`zipCode`
   		FROM userInfoView WHERE ESId=".$ESId." AND `identity` = 1 ORDER BY $sidx $sord LIMIT $start,$limit";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_NUM);
	}
	/**
	 *获取来访客户数据
	 */
	public function getVisitingCustomersData($db,$ESId){
		$sql="SELECT userInfoView.`id`,userInfoView.`roomId`,userInfoView.`alias`,
    userInfoView.`gender`,userInfoView.`mobileNo`,userInfoView.`IDCardNo`,userInfoView.reception
		FROM userInfoView
		WHERE ESId=$ESId  AND `identity` = 2 ORDER BY userInfoView.`id`";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/**
	 *获取来访客户数据
	*/
	public function getVisitingCustomers($db,$ESId,$sidx,$sord,$start,$limit){
		$sql="SELECT userInfoView.`id`,userInfoView.`roomId`,userInfoView.`alias`,
    userInfoView.`gender`,userInfoView.`mobileNo`,userInfoView.`IDCardNo`,userInfoView.`reception`
		FROM userInfoView
		WHERE ESId=$ESId  AND `identity` = 2 ORDER BY $sidx $sord LIMIT $start,$limit";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_NUM);
	}
	/**
	 *获取员工数据(不分页)
	 */
	public function getStaffData($db,$ESId){
		$sql="SELECT `id`,`roomId`,`alias`,`gender`,`mobileNo`,`IDCardNo`
		FROM userInfoView WHERE
		ESId=".$ESId."  AND `identity` = 3 ORDER BY id";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/**
	 *获取员工数据
	*/
	public function getStaff($db,$ESId,$sidx,$sord,$start,$limit){
		$sql="SELECT `id`,`roomId`,`alias`,`gender`,`mobileNo`,`IDCardNo`
		FROM userInfoView WHERE
		ESId=".$ESId." AND `identity` = 3 ORDER BY $sidx $sord LIMIT $start,$limit";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_NUM);
	}
	/**
	 *获取UserInfoView数据总数
	*/
	public function getCountUserInfoView($db,$ESId){
		$sql = "SELECT COUNT(*) as count FROM `userInfoView` WHERE ESId=".$ESId." AND `identity` = 1 ";
		return $db -> getColumn($sql);
	}
	/**
	 *获取注册用户数据总数
	 */
	public function getCountLogon($db,$ESId){
		$sql = "SELECT COUNT(*) as count FROM `userInfo` WHERE `attentionESId` =".$ESId." AND `identity` = 4";
		return $db -> getColumn($sql);
	}
	/**
	 *获取推荐政策数据总数
	 */
	public function getCountRecommendPolicy($db,$ESId){
		$sql = "SELECT COUNT(*) as count FROM `recommendPolicy` WHERE `ESId` =".$ESId;
		return $db -> getColumn($sql);
	}
	/**
	 *获取roomInfoView数据总数
	*/
	public function getCountRoomInfoView($db,$ESId){
		$sql = "SELECT COUNT(*) as count FROM `roomInfoView` WHERE ESId=".$ESId." AND NOT $this->notVisiterAndStaff";
		return $db -> getColumn($sql);
	}
	/**
	 *获取来访客户数据总数
	*/
	public function getCountVisitingCustomers($db,$ESId){
		$sql = "SELECT COUNT(*) as count FROM `userInfoView` WHERE
		ESId=".$ESId." AND `identity` = 2 ";
		return $db -> getColumn($sql);
	}
	/**
	 *获取员工数据总数
	*/
	public function getCountStaff($db,$ESId){
		$sql = "SELECT COUNT(*) as count FROM `userInfoView` WHERE
		ESId=".$ESId." AND `identity` = 3 ";
		return $db -> getColumn($sql);
	}
	/**
	 *获取数据总数
	*/
	public function getCount($db,$tableView,$ESId){
		$sql = "SELECT COUNT(*) as count FROM ".$tableView." WHERE ESId='$ESId'";
		return $db -> getColumn($sql);
	}

	/** 获取paymentProgress表中数据总数
	 * 	by Nico
	 * @param unknown $db
	 * @param unknown $roomId
	 */
	public function getPaymentProgressCount($db,$roomId){
		$sql = "SELECT COUNT(*) as count FROM paymentProgress WHERE roomId='$roomId'";
		return $db -> getColumn($sql);
	}

	/** 获取微活动数据总数
	 *   by Nico
	 * @param unknown $db
	 * @param unknown $ESId
	 * @param unknown $type
	 */
	public function getEventCount($db,$ESId,$type){
		switch ($type){
			case "onGoing":
				$sql = "SELECT COUNT(*) FROM `event` WHERE finishTime>=NOW()";
				break;
			case "past":
				$sql = "SELECT COUNT(*) FROM `event` WHERE finishTime<NOW()";
				break;
		}
		return $db -> getColumn($sql);
	}

	/** 查询此手机是否已经存在mobileInfo表中，如果不存在则插入一条
	 *   by Nico
	 * @param unknown $db
	 * @param unknown $mobileNo
	 */
	public function issetMobileNo($db,$mobileNo){
		$sql = "SELECT id FROM mobileInfo WHERE mobileInfo.mobileNo='$mobileNo'";
		$mobileId = $db -> getColumn($sql);
		if(!$mobileId){
			$mobileInfo['mobileNo'] = $mobileNo;
			$mobileInfo['account'] = '';
			$this->insertOper($db, 'mobileInfo', $mobileInfo);
		}
	}

	/** 查询业主是否存在userInfo表中，不存在则插入
	 * by Nico
	 * @param unknown $db
	 * @param unknown $alias
	 * @param unknown $mobileId
	 * @param unknown $roomId
	 * @return unknown
	 */
	public function insertUserInfoByMobileIdAndRoomId($db,$alias,$mobileId,$roomId){
		$sql = "SELECT id FROM userInfo WHERE mobileId='$mobileId' AND roomId='$roomId' AND `identity` = 1";
		$result = $db -> getColumn($sql);
		if(!$result){
					$userInfo['alias'] = $alias;
					$userInfo['mobileId'] = $mobileId;
					$userInfo['roomId'] = $roomId;
					$userInfo['identity'] = 1;
					$this->insertOper($db, 'userInfo', $userInfo);
		}else{
					//这里不能进来，如果进来就错了。插入新房屋的时候，不可能已经有该房屋的用户了。
			}

		return $result;
	}

	public function updateUserInfoByMobileIdAndRoomId($db,$alias,$mobileId,$roomId) {
		$oldMobileId = $this->getMobileIdByRoomId($db, $roomId);
		$sqlUserId = "SELECT id FROM userInfo WHERE mobileId=$oldMobileId AND roomId=$roomId AND `identity` = 1";
		$userId = $db->getColumn($sqlUserId);
		if ($userId) {
			$userInfoUpd['mobileId']=$mobileId;
			$userInfoUpd['alias']=$alias;
			$userInfoUpd['identity'] = 1;
			$userInfoUpdCondition['id']=$userId;
			$this->updateOper($db,'userInfo',$userInfoUpd,$userInfoUpdCondition);
		} else {//这里根本就进不来
			$userInfo['alias'] = $alias;
			$userInfo['mobileId'] = $mobileId;
			$userInfo['roomId'] = $roomId;
			$userInfo['identity'] = 1;
			$this->insertOper($db, 'userInfo', $userInfo);
		}

	}

	/** 执行来访客户信息编辑增删改操作
	 * by Nico
	 * @param unknown $db
	 * @param unknown $oper
	 * @param unknown $userInfo
	 * @param unknown $roomExtInfo
	 */
	public function visitingCustomersEdit($db,$oper,$userInfo,$roomExtInfo){
		try{
			$db -> beginTran();
			switch ($oper){
				case "add":
					$userInfo['mobileId'] = $this -> getMobileIdByMobileNo($db,$userInfo['mobileNo']);//获得手机id
					unset($userInfo['mobileNo']);
					unset($userInfo['id']);
					$this->insertOper($db, 'userInfo', $userInfo);
					break;
				case "edit":
					$userInfo['mobileId'] = $this -> getMobileIdByMobileNo($db,$userInfo['mobileNo']);
					unset($userInfo['mobileNo']);
					$condition['id'] = $userInfo['id'];
					$this->updateOper($db, 'userInfo', $userInfo, $condition);
					break;
				case "del":
					$condition['id'] = $userInfo['id'];
					$this->deleteOper($db, 'userInfo', $condition);
					break;
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "Failed:  ".$e->getMessage();
		}
	}
	/** 执行员工信息编辑增删改操作
	 * by Nico
	 * @param unknown $db
	 * @param unknown $oper
	 * @param unknown $userInfo
	 */
	public function staffEdit($db,$oper,$userInfo){
		try{
			$db -> beginTran();
			switch ($oper){
				case "add":
					$userInfo['mobileId'] = $this -> getMobileIdByMobileNo($db,$userInfo['mobileNo']);//获得手机id
					unset($userInfo['mobileNo']);
					unset($userInfo['id']);
					$this->insertOper($db, 'userInfo', $userInfo);
					break;
				case "edit":
					$userInfo['mobileId'] = $this -> getMobileIdByMobileNo($db,$userInfo['mobileNo']);
					unset($userInfo['mobileNo']);
					$condition['id'] = $userInfo['id'];
					$this->updateOper($db, 'userInfo', $userInfo, $condition);
					break;
				case "del":
					$condition['id'] = $userInfo['id'];
					$this->deleteOper($db, 'userInfo', $condition);
					break;
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "Failed:  ".$e->getMessage();
		}
	}

	/** 执行客户信息编辑增删改操作
	 * by Nico
	 * @param unknown $db
	 * @param unknown $oper
	 * @param unknown $userInfo
	 */
	public function userInfoEdit($db,$oper,$userInfo){
		try{
			$db -> beginTran();
			switch ($oper){
				case "add":
					$userInfo['mobileId'] = $this -> getMobileIdByMobileNo($db,$userInfo['mobileNo']);//获得手机id
					unset($userInfo['mobileNo']);
					unset($userInfo['id']);
					$this->insertOper($db, 'userInfo', $userInfo);
					break;
				case "edit":
					$userInfo['mobileId'] = $this -> getMobileIdByMobileNo($db,$userInfo['mobileNo']);
					unset($userInfo['mobileNo']);
					$condition['id'] = $userInfo['id'];
					$this->updateOper($db, 'userInfo', $userInfo, $condition);
					break;
				case "del":
					$condition['id'] = $userInfo['id'];
					$this->deleteOper($db, 'userInfo', $condition);
					break;
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "Failed:  ".$e->getMessage();
		}
	}
	/** 执行注册用户信息编辑增删改操作
	 * by Nico
	 * @param unknown $db
	 * @param unknown $oper
	 * @param unknown $logonInfo
	 */
	public function logonInfoEdit($db,$oper,$logonInfo){
		try{
			$db -> beginTran();
			switch ($oper){
				case "add":
					$logonInfo['mobileId'] = $this -> getMobileIdByMobileNo($db,$logonInfo['mobileNo']);//获得手机id
					unset($logonInfo['mobileNo']);
					unset($logonInfo['id']);
					$this->insertOper($db, 'userInfo', $logonInfo);
					break;
				case "edit":
					$logonInfo['mobileId'] = $this -> getMobileIdByMobileNo($db,$logonInfo['mobileNo']);
					unset($logonInfo['mobileNo']);
					$condition['id'] = $logonInfo['id'];
					$this->updateOper($db, 'userInfo', $logonInfo, $condition);
					break;
				case "del":
					$condition['id'] = $logonInfo['id'];
					$this->deleteOper($db, 'userInfo', $condition);
					break;
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "Failed:  ".$e->getMessage();
		}
	}
	/** 楼盘主页背景图编辑增删改操作
	 * by Nico
	 * @param unknown $db
	 * @param unknown $oper
	 * @param unknown $bgImg
	 */
	public function bgImgEdit($db,$oper,$bgImg){
		try{
			$db -> beginTran();
			switch ($oper){
				case "add":
					$this->insertOper($db, 'bgImg', $bgImg);
					break;
				case "edit":
					$condition['id'] = $bgImg['id'];
					$this->updateOper($db, 'bgImg', $bgImg, $condition);
					break;
				case "del":
					$condition['id'] = $bgImg['id'];
					$this->deleteOper($db, 'bgImg', $condition);
					break;
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "Failed:  ".$e->getMessage();
		}
	}
	/** 执行注册用户信息编辑增删改操作
	 * by Nico
	 * @param unknown $db
	 * @param unknown $oper
	 * @param unknown $recommendPolicy
	 */
	public function recommendPolicyEdit($db,$oper,$recommendPolicy){
		try{
			$db -> beginTran();
			switch ($oper){
				case "add":
					$this->insertOper($db, 'recommendPolicy', $recommendPolicy);
					break;
				case "edit":
					$condition['id'] = $recommendPolicy['id'];
					$this->updateOper($db, 'recommendPolicy', $recommendPolicy, $condition);
					break;
				case "del":
					$condition['id'] = $recommendPolicy['id'];
					$this->deleteOper($db, 'recommendPolicy', $condition);
					break;
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "Failed:  ".$e->getMessage();
		}
	}

	/** 执行房屋信息编辑增删改操作
	 * by Nico
	 * @param unknown $db
	 * @param unknown $oper
	 * @param unknown $alias
	 * @param unknown $roomInfo
	 * @param unknown $roomExtInfo
	 */
	public function roomInfoEdit($db,$oper,$alias,$roomInfo,$roomExtInfo){
		try{
			$db -> beginTran();
			switch ($oper){
				case "add":
					//roomInfo表相关操作
					$roomInfo['ownerMobileId'] = $this -> getMobileIdByMobileNo($db,$roomInfo['mobileNo']);//获得手机id
					unset($roomInfo['mobileNo']);
					unset($roomInfo['id']);
					$roomId = $this -> insertOper($db, 'roomInfo', $roomInfo);
					$this -> insertUserInfoByMobileIdAndRoomId($db, $alias, $roomInfo['ownerMobileId'],$roomId);
					//roomExtInfo表相关操作
					$roomExtInfo['roomId'] = $roomId;
					$this -> insertOper($db, 'roomExtInfo', $roomExtInfo);
					break;
				case "edit":
					$roomInfo['ownerMobileId'] = $this -> getMobileIdByMobileNo($db,$roomInfo['mobileNo']);
					unset($roomInfo['mobileNo']);
					$this -> updateUserInfoByMobileIdAndRoomId($db, $alias, $roomInfo['ownerMobileId'],$roomInfo['id']);
					$roomInfoCondition['id'] = $roomInfo['id'];
					$this -> updateOper($db, 'roomInfo', $roomInfo, $roomInfoCondition);
					//roomExtInfo表相关操作
					//如果roomExtInfo表中无roomId的相关记录，则更新会失败（不应该没有）
					$roomExtInfo['roomId'] = $roomInfo['id'];
					$roomExtInfoCondition['roomId'] = $roomInfo['id'];
					$this->updateOper($db, 'roomExtInfo', $roomExtInfo, $roomExtInfoCondition);

					break;
				case "del":
					$condition['id'] = $roomInfo['id'];
					$this->deleteOper($db, 'roomInfo', $condition);
					break;
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "Failed:  ".$e->getMessage();
		}
	}


	/** 获得管理员等级
	 * by Nico
	 * @param unknown $db
	 * @param unknown $adminId
	 */
	public function getAdminLevelById($db, $adminId){
		$sql = "SELECT `level` FROM admin WHERE id='$adminId'";
		return $db -> getColumn($sql);
	}


	/** 获得房屋名称列表
	 * by Nico
	 * @param unknown $db
	 * @param unknown $ESId
	 */
	public function getListRoom($db,$ESId){
		$sql = "SELECT `roomId`,`roomName` FROM roomNameView WHERE ESId='$ESId' AND NOT $this->notVisiterAndStaff GROUP BY roomId ORDER BY roomName";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/**
	 * 下拉菜单要用到，已经废弃
	 * 获得房型名称列表
	 * by Nico
	 * @param unknown $db
	 * @param unknown $ESId
	 */
	public function getListRoomTypeName($db,$ESId){
		$sql = "SELECT `id`,`name` FROM roomTypeInfo WHERE ESId='$ESId' GROUP BY id ORDER BY name";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}

	/**
	 * 通过楼盘id获取当前楼盘下有多少条住户信息
	 * $ESId   所属楼盘id
	*/
	public function getOwnerNumByESId($db , $ESId ){
		$sql = "SELECT COUNT(*) FROM roomInfo WHERE ESId=$ESId";
		return $db->fetchData($sql,NULL,PDO::FETCH_NUM);
	}

	/**
	 *  通过$ESId获取住户信息
	* $ESId   所属楼盘id
	* $sidx 获取索引行-即用户点击排序
	* $sord 按什么排序
	* $start  从第几条开始去(下标从0开始)
	* $limit  取多少条
	*/
	public function getOwnerData($db,$ESId,$sidx,$sord,$start,$limit){
		$sql="SELECT id,gardenName,buildingNo,doorNo,floorNo,roomNo,teleNo,`level`
FROM roomInfo WHERE ESId=$ESId ORDER BY ".$sidx."  ".$sord." LIMIT ".$start." ,".$limit."";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/**
	 *  通过$ESId获取用户信息
	 * $ESId   所属楼盘id
	 * $sidx 获取索引行-即用户点击排序
	 * $sord 按什么排序
	 * $start  从第几条开始去(下标从0开始)
	 * $limit  取多少条
	 */
	public function getUserData($db,$ESId,$sidx,$sord,$start,$limit){
		$sql="SELECT userInfo.id,userInfo.alias,mobileInfo.mobileNo,userInfo.identity,mobileInfo.account,concat(ifnull(`roomInfo`.`gardenName`,''),if((`roomInfo`.`buildingNo` = 0),'',concat(`roomInfo`.`buildingNo`,'号楼')),if((`roomInfo`.`doorNo` = 0),'',concat(`roomInfo`.`doorNo`,'门')),if((`roomInfo`.`floorNo` = 0),'',concat(`roomInfo`.`floorNo`,'层')),if((`roomInfo`.`roomNo` = 0),'',concat(`roomInfo`.`roomNo`,'室'))) AS `roomName`  FROM userInfo LEFT JOIN mobileInfo ON userInfo.mobileId = mobileInfo.id LEFT JOIN roomInfo ON userInfo.roomId=roomInfo.id WHERE userInfo.attentionESId =$ESId OR roomInfo.ESId=$ESId ORDER BY ".$sidx."  ".$sord." LIMIT ".$start." ,".$limit."";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/**
	 * 通过楼盘id获取当前楼盘下有多少条用户信息
	 * $ESId   所属楼盘id
	 */
	public function getOwnerUserNumByESId($db , $ESId ){
		$sql = "SELECT COUNT(*) FROM roomInfo WHERE ESId=$ESId";
		return $db->fetchData($sql,NULL,PDO::FETCH_NUM);
	}
	/**
	 * 通过楼盘id获取当前楼盘下有多少条用户
	 * $ESId   所属楼盘id
	 */
	public function getUserNumByESId($db , $ESId ){
		$sql = "SELECT COUNT(*) FROM userInfo LEFT JOIN roomInfo ON userInfo.`roomId`=roomInfo.`id` WHERE userInfo.`attentionESId`=$ESId OR roomInfo.`ESId`=$ESId";
		return $db->fetchData($sql,NULL,PDO::FETCH_NUM);
	}
	/*
	 *  添加多条通知的数据
	*  $values 插入数据的数组
	*/
	public function addNotifyAll($db,$values){
		$sql="INSERT INTO notifyAll(notifyId,roomId) VALUES";
		$sql = $sql.$values;
		return $db->executeOne($sql);
	}
	/*
	 *  添加多条用户通知的数据
	*  $values 插入数据的数组
	*/
	public function addUserNotifyAll($db,$values){
		$sql="INSERT INTO eventNotifyStatus (notifyId,userId) VALUES";
		$sql = $sql.$values;
		return $db->executeOne($sql);
	}
	/*
	 *获取小区通知数据集
	* $values   查找条件
	*/
	public function getNotifyList($db,$values){
		$sql="SELECT roomId,roomName,`type`,identifyNo,mobileNo,v_sendnotifylist.userId FROM v_sendnotifylist WHERE (".$values.") AND logonStatus=1";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/*通过楼盘id获取当前楼盘下有多少条住户信息
	 * $ESId   所属楼盘id
	*/
	public function getNotifyStatusNumByESId($db , $ESId ){
		$sql="SELECT COUNT(*) as count FROM v_notifystatus WHERE ESId=".$ESId;
		return $db->fetchData($sql,NULL,PDO::FETCH_NUM);
	}
	/*通过楼盘id获取当前楼盘下有多少条住户信息（用户通知管理）
	 * $ESId   所属楼盘id
	*/
	public function getUserNotifyStatusNumByESId($db , $ESId ){
		$sql="SELECT COUNT(*) as count FROM v_eventNotifyStatus WHERE ESId=".$ESId;
		return $db->fetchData($sql,NULL,PDO::FETCH_NUM);
	}
	/*
	 *  通过$ESId获取v_notifystatus视图数据
	* $ESId   所属楼盘id
	* $sidx 获取索引行-即用户点击排序
	* $sord 按什么排序
	* $start  从第几条开始去(下标从0开始)
	* $limit  取多少条
	*/
	public function getNnotifyStatus($db,$ESId,$sidx,$sord,$start,$limit){
		$sql="SELECT (@i:=@i+1) AS id,notifyAllId AS notifyId,title,urgency,roomName,mobileNo,roomId,readStatus,delStatus
   FROM v_notifystatus ,(SELECT @i:=0) AS foo WHERE ESId='".$ESId."'
   		ORDER BY ".$sidx."  ".$sord." LIMIT ".$start." ,".$limit."";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/*
	 *  通过$ESId获取v_eventNotifyStatus视图数据
	* $ESId   所属楼盘id
	* $sidx 获取索引行-即用户点击排序
	* $sord 按什么排序
	* $start  从第几条开始去(下标从0开始)
	* $limit  取多少条
	*/
	public function getUserNotifyStatus($db,$ESId,$sidx,$sord,$start,$limit){
		$sql="SELECT (@i:=@i+1) AS `id`,`v_eventNotifyStatus`.`id` AS `notifyId`,`v_eventNotifyStatus`.`title`,`v_eventNotifyStatus`.`urgency`,`mobileInfo`.`account`,`mobileInfo`.`mobileNo`,`v_eventNotifyStatus`.`readStatus`,`v_eventNotifyStatus`.`delStatus`
   FROM `v_eventNotifyStatus`
LEFT JOIN `userInfo` ON  `userInfo`.`id` = `v_eventNotifyStatus`.`eventUserId`
LEFT JOIN `mobileInfo` ON `mobileInfo`.`id` = `userInfo`.`mobileId`
 ,(SELECT @i:=0) AS `foo`
WHERE `ESId`=".$ESId."
   		ORDER BY ".$sidx."  ".$sord." LIMIT ".$start." ,".$limit."";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/*
	 *  通过$ESId获取被推荐人数据个数（不含重复数据）
	* $ESId   所属楼盘id
	*/
	public function getRecommendNumData($db,$ESId){
		$sql="SELECT id,beRecommendName ,beRecommendTel,SUM(stateId) as state
                 FROM recommend WHERE ESId='".$ESId."'
   		 GROUP BY beRecommendTel ORDER BY state asc ,recommendTime DESC ";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/*
	 *  通过$ESId获取被推荐人数据
	* $ESId   所属楼盘id
	* $start  从第几条开始去(下标从0开始)
	* $limit  取多少条
	*/
	public function getRecommendData($db,$ESId,$start,$limit){
		$sql="SELECT id,beRecommendName ,beRecommendTel,SUM(stateId) as state
                 FROM recommend WHERE ESId='".$ESId."'
   		 GROUP BY beRecommendTel ORDER BY state  LIMIT ".$start." ,".$limit."";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
/*
 *  通过$ESId、$mobileNo获取推荐人数据
* $ESId   所属楼盘id
* $mobileNo  被推荐的手机号
*/
public function getRecommendBymobile($db,$ESId,$mobileNo){
	$sql="SELECT re.id,u.alias,m.mobileNo,re.recommendTime,re.stateId FROM `recommend` re
 LEFT JOIN userInfo u on re.recommendUserId=u.id
LEFT JOIN mobileInfo m on u.mobileId=m.id
WHERE re.ESId='".$ESId."'  and re.beRecommendTel='".$mobileNo."'  ORDER BY re.recommendTime desc";
	return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
}
/*
 *  通过$ESId获取推荐成功和已经确认的的客户数量
* $ESId   所属楼盘id
*/
public function getSuccessRecommendNum($db,$ESId){
// 	$sql="SELECT r.id,r.beRecommendName,r.beRecommendTel,
//  u.alias,m.mobileNo,r.recommendTime,r.complete
//  FROM `recommend` r
// LEFT JOIN userInfo u on u.id=r.recommendUserId
// LEFT JOIN mobileInfo m on m.id=u.mobileId
// WHERE r.ESId='".$ESId."' and r.stateId='2'
// ORDER BY r.complete ASC,r.recommendTime DESC";
// 	return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	$sql="SELECT COUNT(*) AS numCount FROM recommend WHERE ESId=$ESId AND stateId<>1";
	return $db->getColumn($sql);
}
	/*
	 *  通过$ESId获取推荐成功和已经确认的客户数据（有分页）
	* $ESId   所属楼盘id
	*/
	public function getSuccessRecommendPage($db,$ESId,$start,$limit){
		$sql="SELECT r.id,r.beRecommendName,r.beRecommendTel,
	 u.alias,m.mobileNo,r.recommendTime,r.`stateId`
	 FROM `recommend` r
	LEFT JOIN userInfo u ON u.id=r.recommendUserId
	LEFT JOIN mobileInfo m ON m.id=u.mobileId
	WHERE r.ESId=$ESId AND r.stateId<>'1'
	ORDER BY r.stateId ASC,r.recommendTime DESC LIMIT ".$start." ,".$limit."";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/*
	 *  通过$retel更新客户推荐表数据（更新id不等于$id的被推荐用户手机号为$retel的都设为失效1）
	* $ESId   所属楼盘id
	* $id       不更新的id
	* $retel   被推荐人手机号
	*/
	public function updateRecommendByRetel($db,$ESId,$id,$retel){
		$sql="UPDATE recommend SET stateId=1 WHERE beRecommendTel='".$retel."' and id <> ".$id." and ESId=".$ESId."";
		return $db->execute($sql);
	}
	/*
	 *  通过$id更新客户推荐表数据(把id等于$id 状态值设为确认推荐2）
	* $ESId   所属楼盘id
	* $id       推荐表id
	*/
	public function updateRecommendById($db,$ESId,$id){
		$sql="UPDATE recommend SET stateId=2 WHERE id = ".$id." and ESId=".$ESId."";
		return $db->execute($sql);
	}
	/*
	 *  通过$retel更新客户推荐表数据(把$retel 的所有推荐人状态值设为失效1）
	 		* $ESId   所属楼盘id
	 		* $retel   被推荐人手机号
	 		*/
	public function updateFailureRecommend($db,$ESId,$retel){
		$sql="UPDATE recommend SET stateId=1 WHERE beRecommendTel='".$retel."' and ESId=".$ESId."";
		return $db->execute($sql);
	}
	/*
	 *  通过$id更新客户推荐表数据(把id等于$id complete值设为已发奖1）
	 		* $ESId   所属楼盘id
	 		* $id       推荐表id
	 		*/
	public function updateAwardstate($db,$ESId,$id){
		$sql="UPDATE recommend SET stateId=3 WHERE id = ".$id." and ESId=".$ESId." ";
		return $db->execute($sql);
	}
	/*
	 *  通过$ESId获取推荐活动数据
	*/
	public function getRecommendAward($db){
		$sql="SELECT id,ESId,content,award FROM  recommendAward";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/*
	 *  通过$ESId获取推荐活动数据
	* $ESId   所属楼盘id
	*/
	public function getRecommendAwardByESId($db,$ESId){
		$sql="SELECT id,ESId,content,award FROM  recommendAward where ESId=".$ESId."";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/**
	 * 房型管理会用到，已经废弃
	 *  通过$ESId获取房型数据（有分页）
	* $ESId   所属楼盘id
	*/
	public function getRoomTypeInfo($db,$ESId,$sidx,$sord,$start,$limit){
		$sql="SELECT id,`name`,lounge,hall,kitchen,bathroom,areaNo FROM `roomTypeInfo` WHERE ESId=".$ESId."
				ORDER BY ".$sidx."  ".$sord." LIMIT ".$start." ,".$limit."";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/**
	 *  通过$ESId、$name查询房型表中是否已有此名称，已经废弃
	* $ESId   所属楼盘id
	* $name 房型名称
	*/
	public function getRoomTypeInfoByname($db,$ESId,$name){
		$sql="SELECT id FROM roomTypeInfo WHERE ESId = ".$ESId." and `name`='$name'";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/**
	 *  通过$ESId 获取当前楼盘下通知信息条数
	 * @param unknown $db
	 * @param number  $ESId
	 */
	public function getNotifyInfoCount($db,$ESId){
		$sql = "SELECT COUNT(*) as total FROM
				(SELECT id,pubTime,title,content,picture,URL,urgency
				FROM `v_notifystatus` WHERE ESId=$ESId GROUP BY id) tempdata";
		return $db -> getColumn($sql);
	}
	/**
	 *  通过$ESId 获取当前楼盘下用户通知信息条数
	 * @param unknown $db
	 * @param number  $ESId
	 */
	public function getUserNotifyInfoCount($db,$ESId){
		$sql = "SELECT COUNT(*) as total FROM
		(SELECT id FROM `v_eventNotifyStatus` WHERE ESId=$ESId GROUP BY id) tempdata";
		return $db -> getColumn($sql);
	}
	/**
	 *  通过$ESId 获取当前楼盘下公共通知信息条数
	 * @param unknown $db
	 * @param number  $ESId
	 */
	public function getPublicNotifyCount($db,$ESId){
		$sql = "SELECT COUNT(*) as total FROM `v_publicNotify` WHERE ESId=$ESId";
		return $db -> getColumn($sql);
	}
	/**
	 *  通过$ESId 获取当前楼盘下公共通知信息数据
	 * @param unknown $db
	 * $ESId   所属楼盘id
	 * $start  从第几条开始去(下标从0开始)
	 * $limit  取多少条
	 */
	public function getPublicNotifyData($db,$ESId,$start,$limit){
		$sql="SELECT id,title,content,picture,URL,pubTime,IF(`urgency`=1,'是','否') AS urgency,viewCount FROM `v_publicNotify`
			  WHERE ESId=$ESId GROUP BY id ORDER BY id DESC  LIMIT ".$start." ,".$limit."";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/**
	 *  通过$ESId获取当前楼盘下通知信息数据
	 * $ESId   所属楼盘id
	 * $start  从第几条开始去(下标从0开始)
	 * $limit  取多少条
	 */
	public function getNotifyInfoData($db,$ESId,$start,$limit){
		$sql="SELECT id,title,content,picture,URL,pubTime,IF(`urgency`=1,'是','否') AS urgency FROM `v_notifystatus`
			  WHERE ESId=$ESId GROUP BY id ORDER BY id DESC  LIMIT ".$start." ,".$limit."";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/**
	 *  通过$ESId获取当前楼盘下用户通知信息数据
	 * $ESId   所属楼盘id
	 * $start  从第几条开始去(下标从0开始)
	 * $limit  取多少条
	 */
	public function getUserNotifyInfoData($db,$ESId,$start,$limit){
		$sql="SELECT id,title,content,picture,URL,pubTime,IF(`urgency`=1,'是','否') AS urgency FROM `v_eventNotifyStatus`
		WHERE ESId=$ESId GROUP BY id ORDER BY id DESC  LIMIT ".$start." ,".$limit."";
				return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/** 通知信息增删改操作
	 * @param unknown $db
	 * @param unknown $oper
	 * @param unknown $id
	 * @param unknown $notifyInfo
	 */
	public function notifyEdit($db,$oper,$id,$notifyInfo){
		try{
			$db -> beginTran();
			switch ($oper){
				case "add":
					$this -> insertOper($db, 'v_notifystatus', $notifyInfo);
					break;
				case "edit":
					$condition['id'] = $id;
					$this->updateOper($db, 'v_notifystatus', $notifyInfo, $condition);
					break;
				case "del":
					$condition['id'] = $id;
					$this->deleteOper($db,'notify', $condition);
					break;
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "Failed:  ".$e->getMessage();
		}
	}
	/** 用户通知信息增删改操作
	 * @param unknown $db
	 * @param unknown $oper
	 * @param unknown $id
	 * @param unknown $notifyInfo
	 */
	public function UserNotifyEdit($db,$oper,$id,$notifyInfo){
		try{
			$db -> beginTran();
			switch ($oper){
				case "add":
					$this -> insertOper($db, 'v_eventNotifyStatus', $notifyInfo);
					break;
				case "edit":
					$condition['id'] = $id;
					$this->updateOper($db, 'v_eventNotifyStatus', $notifyInfo, $condition);
					break;
				case "del":
					$condition['id'] = $id;
					$this->deleteOper($db,'notify', $condition);
					break;
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "Failed:  ".$e->getMessage();
		}
	}
	/** 公众通知信息增删改操作
	 * @param unknown $db
	 * @param unknown $oper
	 * @param unknown $id
	 * @param unknown $Notify
	 * @param unknown $public
	 */
	public function publicNotifyEdit($db,$oper,$id,$notify,$public){
		try{
			$db -> beginTran();
			switch ($oper){
				case "add":
					$public['notifyId'] = $this -> insertOper($db, 'notify', $notify);
					$this -> insertOper($db,'publicNotify', $public);
					break;
				case "edit":
					$condition['id'] = $id;
					$this->updateOper($db,'notify', $notify, $condition);
// 					$publicCondition['notifyId']= $id;
// 					$this->updateOper($db,'publicNotify', $public, $publicCondition);

					break;
				case "del":
					$condition['id'] = $id;
					$this->deleteOper($db,'notify', $condition);
					break;
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "Failed:  ".$e->getMessage();
		}
	}
	//**BEGIN*******************************************王津***************************************
	/**
	 * 获取全景观楼里面的公共部分
	 * @param unknown $db
	 * @param number $BTId
	 * @param number $ESId
	 */
	public function getBrochureCommon($db,$BTId,$ESId){
		$sql = "SELECT brochure.id,brochure.picture,brochure.intro,brochure.enjoy,COUNT(brochureComment.id) AS commentCount FROM brochure
		LEFT JOIN brochureComment ON brochureComment.brochureId=brochure.id WHERE BTId=$BTId AND ESId=$ESId";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/**
	 * 获取评论
	 * @param unknown $db
	 * @param unknown $ESId
	 */
	public function getComment($db,$ESId,$sidx,$sord,$start,$limit) {
		$sql = "SELECT id,alias,postTime,userId,content,brochureId,audit FROM v_brochureComment WHERE ESId=$ESId ORDER BY $sidx $sord LIMIT $start ,$limit;";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_NUM);
	}
	/**
	 * 获取评论个数
	 * @param unknown $db
	 * @param unknown $ESId
	 */
	public function getCommentCount($db,$ESId) {
		$sql = "SELECT COUNT(*) AS count FROM v_brochureComment WHERE ESId=$ESId";
		return $db->getColumn($sql);
	}
	/**
	 * 获取图片墙的个数
	 * @param unknown $db
	 * @param unknown $ESId
	 * @param unknown $BTId
	 */
	public function getPicWallCount($db,$ESId,$BTId){
		$sql = "SELECT COUNT(*) FROM brochurePic
LEFT JOIN brochure ON brochure.`id`=brochurePic.`brochureId`
WHERE ESId=$ESId AND BTId=$BTId";
		return $db->getColumn($sql);
	}
	/**
	 * 获取图片墙数据
	 * @param unknown $db
	 * @param unknown $ESId
	 * @param unknown $BTId
	 */
	public function getPicWall($db,$ESId,$BTId,$sidx,$sord,$start,$limit) {
		$sql = "SELECT brochurePic.id,brochureId,brochurePic.picture,`comment` FROM brochurePic
		LEFT JOIN brochure ON brochure.`id`=brochurePic.`brochureId`
		WHERE ESId=$ESId AND BTId=$BTId
		ORDER BY $sidx $sord LIMIT $start ,$limit";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_NUM);
	}
	/**
	 * 获取全景观楼里面区域的种类个数
	 * @param unknown $db
	 */
	public function getAreaCount($db) {
		$sql = "SELECT COUNT(*) AS count FROM brochureAreaTypeInfo";
		return $db->getColumn($sql);
	}
	public function getArea($db,$ESId,$sidx,$sord,$start,$limit) {
		$sql = "SELECT (@i:=@i+1) AS id,IFNULL(brochureArea.`id`,RAND()) AS BAId,brochureAreaTypeInfo.`areaType`,IFNULL(brochureArea.`content`,'<未设置>'),brochureAreaTypeInfo.`id` AS BATId FROM brochureAreaTypeInfo
LEFT JOIN (SELECT @i:=0) AS foo ON 1=1
LEFT JOIN brochureArea ON brochureArea.`BATId`=brochureAreaTypeInfo.id AND brochureArea.`ESId`=$ESId ORDER BY $sidx $sord LIMIT $start ,$limit;";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_NUM);
	}
	/**
	 * 获取全景观楼的视频
	 * @param unknown $db
	 * @param unknown $ESId
	 */
	public function getBrochureVideo($db,$ESId){
		$sql=" SELECT brochureVideo.id,videoURL,videoThumPic FROM brochureVideo LEFT JOIN brochure ON brochureVideo.brochureId=brochure.id WHERE brochure.ESId=$ESId AND brochure.BTId=1";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/**
	 * 获取楼盘电话
	 * @param unknown $db
	 * @param unknown $ESId
	 */
	public function getEstateTeleInfo($db,$ESId){
		$sql=" SELECT id,department,teleNo,picture FROM estateTeleInfo WHERE ESId=$ESId";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/**
	 * 获取楼盘微博账号
	 * @param unknown $db
	 * @param unknown $ESId
	 */
	public function getWeibo($db,$ESId){
		$sql = "SELECT weiboAccount FROM estateInfo WHERE id=$ESId";
		return $db->getColumn($sql);
	}

	public function getIdByESIdAndBTidFromBrochure($db,$ESId,$BTId) {
		$sql= "SELECT id FROM brochure WHERE ESId=$ESId AND BTId=$BTId";
		return $db->getColumn($sql);
	}

	public function getProvinceList($db){
		$sql = "SELECT id,province FROM provinceInfo";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}

	public function getCitisList($db,$provinceId) {
		$sql = "SELECT id,city FROM cityInfo WHERE provinceId=$provinceId";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}

	public function getCountyList($db,$cityId) {
		$sql = "SELECT id,county FROM countyInfo WHERE cityId=$cityId";
		//echo $sql;
		return $db->fetchAllData($sql,NULL,PDO::FETCH_ASSOC);
	}
	/**
	 * 获取员工房间id
	 * @param unknown $db
	 * @param unknown $ESId
	 * @param unknown $mobileNo
	 * @return unknown
	 */
	public function getStaffRoomId($db,$ESId,$mobileNo){
		$sql = "SELECT id FROM roomInfo WHERE gardenName='' AND buildingNo=0 AND doorNo=0 AND floorNo=0 AND roomNo=1 AND ESId=$ESId";
		//TODO:如果房屋不存在，那么就需要插入一条。然后获取房屋ID
		$staffRoomId=$db->getColumn($sql);
		if($staffRoomId){
			return $staffRoomId;
		}else{
			$mobileId = $this -> getMobileIdByMobileNo($db,$mobileNo);//获得手机id
			$inssql="INSERT INTO roomInfo(buildingNo,doorNo,floorNo,roomNo,ownerMobileId,`level`,ESId) VALUES(0,0,0,1,$mobileId,0,$ESId)";
			$db->executeOne($inssql);
			return $db->getColumn($sql);
		}
	}
	/**
	 * 获取访客房间id
	 * @param unknown $db
	 * @param unknown $ESId
	 * @param unknown $mobileNo
	 * @return unknown
	 */
	public function getVistorRoomId($db,$ESId,$mobileNo){
		$sql = "SELECT id FROM roomInfo WHERE gardenName='' AND buildingNo=0 AND doorNo=0 AND floorNo=0 AND roomNo=0 AND ESId=$ESId";
		$vistorRoomId=$db->getColumn($sql);
		if($vistorRoomId){
			return $vistorRoomId;
		}else{
			$mobileId = $this -> getMobileIdByMobileNo($db,$mobileNo);//获得手机id
			$inssql="INSERT INTO roomInfo(buildingNo,doorNo,floorNo,roomNo,ownerMobileId,`level`,ESId) VALUES(0,0,0,0,$mobileId,0,$ESId)";
			$db->executeOne($inssql);
			return $db->getColumn($sql);
		}
	}
	//**END*******************************************王津*****************************************

	/** 获取房屋信息
	 * by Nico
	 * @param unknown $db
	 * @param unknown $ESId
	 * @param unknown $sidx
	 * @param unknown $sord
	 * @param unknown $start
	 * @param unknown $limit
	 */
	public  function getRoomInfo($db,$ESId,$sidx,$sord,$start,$limit){
		$sql = "SELECT `id`,`alias`,`roomTypeName`,`lounge`,`hall`,`kitchen`,`bathroom`,`areaNo`,`gardenName`,`buildingNo`,`doorNo`,`floorNo`,`roomNo`,`teleNo`,`mobileNo`,
				`regDateTime`,`level`,`emergencyContact`,`emergencyTele`,`dealDate`,`signDate`,`adviser`,`originalPrice`,`dealPrice`,
				`contractPrice`,`downPaymentDate`,`downPayment`,`finalPaymentDate`,`finalPayment`,`loan`,`deedTax`,`maintenanceFunds`,
				`ownershipRegistrationFee`,`otherFee` FROM `roomInfoView` WHERE ESId=".$ESId." AND NOT $this->notVisiterAndStaff ORDER BY $sidx $sord LIMIT $start,$limit";
		return $db->fetchAllData($sql,NULL,PDO::FETCH_NUM);
	}

	/** 通过手机号码获取手机id
	 * by Nico
	 * @param unknown $db
	 * @param unknown $mobileNo
	 */
	public function getMobileIdByMobileNo($db,$mobileNo){
		$sql = "SELECT id FROM mobileInfo WHERE mobileNo='$mobileNo'";
		return  $db -> getColumn($sql);
	}

	/** 通过roomId获取房屋所有者手机id
	 * by Nico
	 * @param unknown $db
	 * @param unknown $roomId
	 */
	public function getMobileIdByRoomId($db,$roomId){
		$sql = "SELECT ownerMobileId FROM roomInfo WHERE id='$roomId'";
		return  $db -> getColumn($sql);
	}

	/** 缴费管理房屋信息
	 * by Nico
	 * @param unknown $db
	 * @param unknown $ESId
	 * @param unknown $sidx
	 * @param unknown $sord
	 * @param unknown $start
	 * @param unknown $limit
	 */
	public  function paymentViewInfo($db,$ESId,$sidx,$sord,$start,$limit){
		$sql = "SELECT `id`,`roomName`,`alias`,`progress` FROM `paymentView` WHERE ESId='$ESId' ORDER BY $sidx $sord LIMIT $start,$limit";
		return $db -> fetchAllData($sql,NULL,PDO::FETCH_NUM);
	}

	/** 缴费详细信息
	 * by Nico
	 * @param unknown $db
	 * @param unknown $roomId
	 * @param unknown $sidx
	 * @param unknown $sord
	 * @param unknown $start
	 * @param unknown $limit
	 */
	public function paymentProgressInfo($db,$roomId,$sidx,$sord,$start,$limit){
		$sql = "SELECT `id`,`content`,`amount`,`postTime`,IF(`status`=1,'已缴','未缴') FROM paymentProgress WHERE roomId='$roomId' ORDER BY $sidx $sord LIMIT $start,$limit";
		return $db -> fetchAllData($sql,NULL,PDO::FETCH_NUM);
	}

	/** 根据roomId获得roomName
	 * by Nico
	 * @param unknown $db
	 * @param unknown $ESId
	 * @param unknown $roomId
	 */
	public function getListRoomByRoomId($db,$ESId,$roomId){
		$sql = "SELECT `roomName` FROM roomNameView WHERE ESId='$ESId' AND roomId='$roomId'";
		return  $db -> getColumn($sql);
	}

	/** 缴费记录增删改操作
	 * by Nico
	 * @param unknown $db
	 * @param unknown $oper
	 * @param unknown $id
	 * @param unknown $paymentProgressInfo
	 */
	public function paymentProgressEdit($db,$oper,$id,$paymentProgressInfo){
		try{
			$db -> beginTran();
			switch ($oper){
				case "add":
					$this -> insertOper($db, 'paymentProgress', $paymentProgressInfo);
					break;
				case "edit":
					$condition['id'] = $id;
					$this->updateOper($db, 'paymentProgress', $paymentProgressInfo, $condition);
					break;
				case "del":
					$condition['id'] = $id;
					$this->deleteOper($db, 'paymentProgress', $condition);
					break;
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "Failed:  ".$e->getMessage();
		}
	}

	/** 工程进度信息
	 * by Nico
	 *
	 */
	public function progressInfo($db,$ESId,$sidx,$sord,$start,$limit){
		$sql = "SELECT `id`,`postTime`,`content` FROM `progress` WHERE ESId='$ESId' ORDER BY $sidx $sord LIMIT $start,$limit";
		return $db -> fetchAllData($sql,NULL,PDO::FETCH_NUM);
	}

	/** 工程进度增删改操作
	 * by Nico
	 *
	 */
	public function progressEdit($db,$oper,$id,$progressInfo){
		try{
			$db -> beginTran();
			switch ($oper){
				case "add":
					$this -> insertOper($db, 'progress', $progressInfo);
					break;
				case "edit":
					$condition['id'] = $id;
					$this->updateOper($db, 'progress', $progressInfo, $condition);
					break;
				case "del":
					$condition['id'] = $id;
					$this->deleteOper($db, 'progress', $condition);
					break;
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "Failed:  ".$e->getMessage();
		}
	}

	/** 微活动信息
	 * by Nico
	 * $type: onGoing是正在进行的活动
	 * 			  past是已经结束的活动
	 */
	public function eventInfo($db,$ESId,$type,$sidx,$sord,$start,$limit){
		switch ($type){
			case "onGoing":
				$sql = "SELECT `id`,`title`,`logo`,`startTime`,`finishTime`,`place`,`summary`,`content`,`interestedNum`,
				`participantsNum`,IF(`useFake`=1,'是','否') FROM `event` WHERE ESId='$ESId' AND finishTime>=NOW() ORDER BY $sidx $sord LIMIT $start,$limit";
				break;
			case "past":
				$sql = "SELECT `id`,`title`,`logo`,`startTime`,`finishTime`,`place`,`summary`,`content`,`interestedNum`,
				`participantsNum`,IF(`useFake`=1,'是','否') FROM `event` WHERE ESId='$ESId' AND finishTime<NOW() ORDER BY $sidx $sord LIMIT $start,$limit";
		}
		return $db -> fetchAllData($sql,NULL,PDO::FETCH_NUM);
	}

	/** 微活动增删改操作
	 * by Nico
	 * @param unknown $db
	 * @param unknown $oper
	 * @param unknown $id
	 * @param unknown $eventInfo
	 */
	public function eventEdit($db,$oper,$id,$eventInfo){
		try{
			$db -> beginTran();
			switch ($oper){
				case "add":
					$this -> insertOper($db, 'event', $eventInfo);
					break;
				case "edit":
					$condition['id'] = $id;
					$this->updateOper($db, 'event', $eventInfo, $condition);
					break;
				case "del":
					$condition['id'] = $id;
					$this->deleteOper($db, 'event', $condition);
					break;
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "Failed:  ".$e->getMessage();
		}
	}

	/** 获得微活动图片墙照片总数
	 * by Nico
	 * @param unknown $db
	 * @param unknown $eventId
	 */
	public function getEventPicCount($db,$eventId){
		$sql = "SELECT COUNT(*) as count FROM eventPic WHERE eventId='$eventId'";
		return $db -> getColumn($sql);
	}

	/** 微活动图片墙信息
	 * by Nico
	 * @param unknown $db
	 * @param unknown $eventId
	 * @param unknown $sidx
	 * @param unknown $sord
	 * @param unknown $start
	 * @param unknown $limit
	 */
	public function eventPicInfo($db,$eventId,$sidx,$sord,$start,$limit){
		$sql = "SELECT `id`,`picture` FROM `eventPic` WHERE `eventId`='$eventId' ORDER BY $sidx $sord LIMIT $start,$limit";
		return $db -> fetchAllData($sql,NULL,PDO::FETCH_NUM);
	}

	/** 微活动图片墙增删改操作
	 * by Nico
	 * @param unknown $db
	 * @param unknown $oper
	 * @param unknown $id
	 * @param unknown $eventInfo
	 */
	public function eventPicEdit($db,$oper,$id,$eventPicInfo){
		try{
			$db -> beginTran();
			switch ($oper){
				case "add":
					$this -> insertOper($db, 'eventPic', $eventPicInfo);
					break;
				case "edit":
					$condition['id'] = $id;
					$this->updateOper($db, 'eventPic', $eventPicInfo, $condition);
					break;
				case "del":
					$condition['id'] = $id;
					$this->deleteOper($db, 'eventPic', $condition);
					break;
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "Failed:  ".$e->getMessage();
		}
	}

	/** 获得已结束微活动总数
	 * by Nico
	 * @param unknown $db
	 * @param unknown $eventId
	 */
	public function getEventReportCount($db,$eventId){
		$sql = "SELECT COUNT(*) as count FROM eventReport WHERE eventId='$eventId'";
		return $db -> getColumn($sql);
	}

	/** 微活动报告
	 * by Nico
	 * @param unknown $db
	 * @param unknown $eventId
	 * @param unknown $sidx
	 * @param unknown $sord
	 * @param unknown $start
	 * @param unknown $limit
	 */
	public function eventReportInfo($db,$eventId,$sidx,$sord,$start,$limit){
		$sql = "SELECT `id`,`title`,`logo`,`reportTime`,`content` FROM `eventReport` WHERE `eventId`='$eventId' ORDER BY $sidx $sord LIMIT $start,$limit";
		return $db -> fetchAllData($sql,NULL,PDO::FETCH_NUM);
	}

	/** 微活动报告增删改操作
	 * by Nico
	 * @param unknown $db
	 * @param unknown $oper
	 * @param unknown $id
	 * @param unknown $eventReportInfo
	 */
	public function eventReportEdit($db,$oper,$id,$eventReportInfo){
		try{
			$db -> beginTran();
			switch ($oper){
				case "add":
					$this -> insertOper($db, 'eventReport', $eventReportInfo);
					break;
				case "edit":
					$condition['id'] = $id;
					$this->updateOper($db, 'eventReport', $eventReportInfo, $condition);
					break;
				case "del":
					$condition['id'] = $id;
					$this->deleteOper($db, 'eventReport', $condition);
					break;
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "Failed:  ".$e->getMessage();
		}
	}

	/** 获得微活动报告图片墙总数
	 * by Nico
	 * @param unknown $db
	 * @param unknown $eventId
	 */
	public function getEventReportPicCount($db,$eventReportId){
		$sql = "SELECT COUNT(*) as count FROM eventReportPicture WHERE eventReportId='$eventReportId'";
		return $db -> getColumn($sql);
	}

	/** 获得微活动报告图片墙信息
	 * by Nico
	 * @param unknown $db
	 * @param unknown $eventReportId
	 * @param unknown $sidx
	 * @param unknown $sord
	 * @param unknown $start
	 * @param unknown $limit
	 */
	public function eventReportPicInfo($db,$eventReportId,$sidx,$sord,$start,$limit){
		$sql = "SELECT `id`,`picture` FROM `eventReportPicture` WHERE `eventReportId`='$eventReportId' ORDER BY $sidx $sord LIMIT $start,$limit";
		return $db -> fetchAllData($sql,NULL,PDO::FETCH_NUM);
	}

	/** 微活动报告图片墙增删改
	 * by Nico
	 * @param unknown $db
	 * @param unknown $oper
	 * @param unknown $id
	 * @param unknown $eventReportPicInfo
	 */
	public function eventReportPicEdit($db,$oper,$id,$eventReportPicInfo){
		try{
			$db -> beginTran();
			switch ($oper){
				case "add":
					$this -> insertOper($db, 'eventReportPicture', $eventReportPicInfo);
					break;
				case "edit":
					$condition['id'] = $id;
					$this->updateOper($db, 'eventReportPicture', $eventReportPicInfo, $condition);
					break;
				case "del":
					$condition['id'] = $id;
					$this->deleteOper($db, 'eventReportPicture', $condition);
					break;
			}
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			echo "Failed:  ".$e->getMessage();
		}
	}

	 /** 生日提醒数据总数
	  * by Nico
	  * @param unknown $db
	  * @param unknown $ESId
	  */
	 public function getBirthdayCount($db,$ESId){
	 	$sql = "SELECT COUNT(*) as count FROM `userInfoView` WHERE CONCAT(SUBSTRING(`IDCardNo`,11,2),SUBSTRING(`IDCardNo`,13,2))=DATE_FORMAT(CURDATE(),'%m%d') AND ESId='$ESId'";
	 	return $db -> getColumn($sql);
	 }

	 /** 获取生日提醒信息
	  * by Nico
	  * @param unknown $db
	  * @param unknown $ESId
	  * @param unknown $sidx
	  * @param unknown $sord
	  * @param unknown $start
	  * @param unknown $limit
	  */
	 public function getBirthdayInfo($db, $ESId,$sidx,$sord,$start,$limit){
	 	$sql = "SELECT `id`,`alias`,`gender`,`mobileNo`,`IDCardNo` FROM `userInfoView` WHERE CONCAT(SUBSTRING(`IDCardNo`,11,2),SUBSTRING(`IDCardNo`,13,2))=DATE_FORMAT(CURDATE(),'%m%d') AND ESId='$ESId' GROUP BY mobileNo";
	 	return $db -> fetchAllData($sql,NULL,PDO::FETCH_NUM);
	 }

	 /** 获得生日祝福
	  * by Nico
	  * @param unknown $db
	  * @param unknown $ESId
	  */
	 public function getBirthdayBlessing($db,$ESId){
	 	$sql = "SELECT `name`,`blessing` FROM estateInfo WHERE id='$ESId'";
	 	return $db -> fetchData($sql,NULL,PDO::FETCH_NUM);
	 }

	 /** 记录客户发送短信条数
	  * by Nico
	  * @param unknown $db
	  * @param unknown $ESId
	  */
	 public function msgCount($db,$ESId,$action,$countNum){
	 	switch ($action){
	 		case "select":
	 			$sql = "SELECT `id` FROM msgCount WHERE ESId='$ESId'";
	 			return $db -> getColumn($sql);
	 			break;
	 		case "insert":
	 			$insetArray['ESId'] = $ESId;
	 			$insetArray['msgNum'] = $countNum;
	 			$this->insertOper($db, 'msgCount', $insetArray);
	 			break;
	 		case "update":
	 			$sql = "UPDATE msgCount SET msgNum=msgNum+'$countNum' WHERE ESId='$ESId'";
	 			return $db -> executeOne($sql);
	 			break;
	 	}
	 }

	 /** 获得楼盘信息
	  * by Nico
	  * @param unknown $db
	  * @param unknown $ESId
	  * @param unknown $sidx
	  * @param unknown $sord
	  * @param unknown $start
	  * @param unknown $limit
	  */
	 public function getEstateInfo($db, $ESId,$sidx,$sord,$start,$limit){
	 	$sql = "SELECT `id`,`province`,`city`,`district`,`street`,`number`,`zipCode`,`name`,`tele`,`admin`,
	 	`blessing`,`GPSx`,`GPSy`,`describe`,`logo`,`weiboAccount` FROM `v_estateInfo` WHERE `id`=$ESId ORDER BY $sidx $sord LIMIT $start,$limit";
	 	return $db -> fetchAllData($sql,NULL,PDO::FETCH_NUM);
	 }

	 /** 修改楼盘信息
	  * by Nico
	  * @param unknown $db
	  * @param unknown $id
	  * @param unknown $estateInfo
	  */
	 public function estateInfoEdit($db,$id,$estateInfo){
	 	try{
	 		$condition['id'] = $id;
	 		$this->updateOper($db, 'estateInfo', $estateInfo, $condition);
	 	}catch(Exception $e){
	 		echo "Failed:  ".$e->getMessage();
	 	}
	 }

	 /** 统计参加活动的人数
	  * by Nico
	  * @param unknown $db
	  * @param unknown $eventId
	  */
	 public function getEventMemberCount($db,$eventId){
	 	$sql = "SELECT COUNT(*) as `count` FROM `eventMember` WHERE `eventId`='$eventId'";
	 	return $db -> getColumn($sql);
	 }

	 /** 参加活动的人员信息
	  * by Nico
	  * @param unknown $db
	  * @param unknown $eventId
	  * @param unknown $sidx
	  * @param unknown $sord
	  * @param unknown $start
	  * @param unknown $limit
	  */
	 public function getEventMemberInfo($db,$eventId,$sidx,$sord,$start,$limit){
	 	$sql = "SELECT `userInfo`.`alias` AS `alias`,`mobileInfo`.`mobileNo` AS `mobileNo`,
CONCAT(IFNULL(`roomInfo`.`gardenName`,''),IFNULL(CONCAT(`roomInfo`.`buildingNo`,'号楼'),''),IFNULL(CONCAT(`roomInfo`.`doorNo`,'门'),''),IFNULL(CONCAT(`roomInfo`.`floorNo`,'层'),''),IFNULL(CONCAT(`roomInfo`.`roomNo`,'室'),'')) AS `roomName` FROM
(((`eventMember` LEFT JOIN `userInfo` ON `eventMember`.`userId`=`userInfo`.`id`)
LEFT JOIN `mobileInfo` ON `userInfo`.`mobileId`=`mobileInfo`.`id`)
LEFT JOIN `roomInfo` ON `userInfo`.`roomId`=`roomInfo`.`id`)
 WHERE `eventMember`.`eventId`='$eventId'";
	 	return $db->fetchAllData($sql,NULL,PDO::FETCH_NUM);
	 }

	 /** 查询合同状态
	  * by Nico
	  * @param unknown $db
	  * @param unknown $loginesid
	  */
	 public function contractState($db,$loginesid){
	 	$sql = "SELECT `ESId` FROM `internalManage` WHERE `ESId`='$loginesid' AND `expirationDate`>=CURRENT_DATE";
	 	return $db -> getColumn($sql);
	 }
	 /** 手机端背景图
	  * by liuchuanqin
	  * @param unknown $db
	  * @param unknown $sidx
	  * @param unknown $sord
	  * @param unknown $start
	  * @param unknown $limit
	  */
	 public function getBgImg($db,$sidx,$sord,$start,$limit){
	 	$sql = "SELECT `id`,`title`,`url` FROM `bgImg` ORDER BY $sidx $sord LIMIT $start,$limit";
	 	return $db -> fetchAllData($sql,NULL,PDO::FETCH_NUM);
	 }
	 /**
	  * updateBgImg 修改楼盘背景图数据
	  * by liuchuanqin
	  * @param unknown $db
	  * @param unknown $id
	  * @param unknown $ESId
	  *
	  */
	 public function updateBgImg($db,$id,$ESId){
	 	$sql="UPDATE `estateInfo` SET `bgImgId` = $id WHERE `id` = $ESId";
	 	return $db->execute($sql);
	 }
	 /**
	  * 手机端背景图
	  */
	 public function getBgImgNum($db){
	 	$sql = "SELECT COUNT(*) AS count  FROM `bgImg`";
	 	return $db -> getColumn($sql);
	 }
	 /**
	  * 通过楼盘id获取当前楼盘的背景id
	  * $ESId   所属楼盘id
	  */
	 public function getBgImgIdByESId($db,$ESId){
	 	$sql = "SELECT `bgImg`.`id` FROM `estateInfo` LEFT JOIN `bgImg` ON `bgImg`.`id` = `estateInfo`.`bgImgId` WHERE `estateInfo`.`id` = ".$ESId;
	 	return $db -> getColumn($sql);
	 }

	 /**
	  * 查询微信新闻数
	  * by Nico
	  * @param unknown $db
	  * @param unknown $ESId
	  */
	 public function getCountWxNewsInfo($db,$ESId){
	 	$sql = "SELECT COUNT(*) AS count FROM `wxNews` WHERE ESId=$ESId";
		return $db->getColumn($sql);
	 }

	 /**
	  * 获取新闻列表
	  * @param unknown $db
	  * @param unknown $ESId
	  * @param unknown $sidx
	  * @param unknown $sord
	  * @param unknown $start
	  * @param unknown $limit
	  */
	 public function getWxNews($db, $ESId,$sidx,$sord,$start,$limit){
	 	$sql="SELECT `id`,`title`,`content`,`picture` FROM `wxNews` WHERE `ESId`=$ESId ORDER BY $sidx $sord LIMIT $start,$limit";
	 	return $db->fetchAllData($sql,NULL,PDO::FETCH_NUM);
	 }

	 /**
	  * 微信新闻增删查改
	  * by Nico
	  * @param unknown $db
	  * @param unknown $oper
	  * @param unknown $newsInfo
	  * @param unknown $id
	  */
	 public function wxNewsInfoEdit($db,$oper,$newsInfo,$id){
	 	try{
	 		$db -> beginTran();
	 		switch ($oper){
	 			case "add":
	 				$this -> insertOper($db, 'wxNews', $newsInfo);
	 				break;
	 			case "edit":
	 				$condition['id'] = $id;
	 				$this->updateOper($db, 'wxNews', $newsInfo, $condition);
	 				break;
	 			case "del":
	 				$condition['id'] = $id;
	 				$this->deleteOper($db, 'wxNews', $condition);
	 				break;
	 		}
	 		$db->commit();
	 	}catch(Exception $e){
	 		$db->rollBack();
	 		echo "Failed:  ".$e->getMessage();
	 	}
	 }
}
